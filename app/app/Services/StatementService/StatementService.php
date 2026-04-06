<?php

declare (strict_types=1);

namespace App\Services\StatementService;

use App\Cache\Entities\StatementEntityCache;
use App\Data\AllStatementsDTO\AllStatementsDTO;
use App\Data\StatementDTO\StatementDTO;
use App\DataAdapters\StatementServiceDataAdapter\StatementServiceDataAdapter;
use App\Enums\ErrorMessages;
use App\Enums\ResponseMessages;
use App\Enums\StatusTransitionType;
use App\Exceptions\InvalidStatusTransitionException;
use App\Exceptions\ServerException;
use App\Models\Statement;
use App\Models\User;
use App\Repositories\StatementRepository\StatementRepository;
use App\Services\StatementService\Strategies\StatusTransitionStrategyResolver;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Contracts\Container\BindingResolutionException;

readonly class StatementService
{
    public function __construct(
        private StatementServiceDataAdapter      $statementServiceDataAdapter,
        private StatementRepository              $statementRepository,
        private StatusTransitionStrategyResolver $strategyResolver,
        private StatementEntityCache             $statementEntityCache,
    ) {}

    public function createStatement(StatementDTO $statementDTO): Statement
    {
        $statement = $this->statementRepository->createByArray($statementDTO->toArray());

        $statementDTO = $this->statementServiceDataAdapter->createResponseStatementDTO($statement);

        $this->statementEntityCache->put($statementDTO->id, $statementDTO->toArray());

        return $statement;
    }

    public function showStatement(int $id): StatementDTO
    {
        $cacheStatement = $this->statementEntityCache->get($id);

        if ($cacheStatement === null) {
            $statement = $this->statementRepository->findById($id);

            if ($statement === null) {
                throw new ModelNotFoundException('Statement not found');
            }

            $statementDTO = $this->statementServiceDataAdapter->createResponseStatementDTO($statement);
            $this->statementEntityCache->put($statementDTO->id, $statementDTO->toArray());

            return $statementDTO;
        }

        return $this->statementServiceDataAdapter->createStatementDTOByArray($cacheStatement);
    }

    public function allStatements(): AllStatementsDTO
    {
        return $this->statementServiceDataAdapter->createAllStatementsDTO(
            $this->statementRepository->getAll(),
        );
    }

    /**
     * @throws ServerException
     * @throws ModelNotFoundException
     */
    public function updateStatement(StatementDTO $updateStatementDTO): StatementDTO
    {
        try {
            $statement = $this->statementRepository->findById($updateStatementDTO->id);

            if ($statement === null) {
                throw new ModelNotFoundException(ErrorMessages::STATEMENT_NOT_FOUND);
            }

            $statement->update([
                'title'  => $updateStatementDTO->title,
                'number' => $updateStatementDTO->number,
                'date'   => $updateStatementDTO->date,
            ]);

            $statementDTO = $this->statementServiceDataAdapter->createResponseStatementDTO($statement);

            $this->statementEntityCache->put(
                id: $updateStatementDTO->id,
                payload: $statementDTO->toArray(),
            );

            return $statementDTO;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Throwable $exception) {
            Log::error($exception->getMessage(), ['exception' => $exception]);
            throw new ServerException(ErrorMessages::SERVER_ERROR);
        }
    }

    public function deleteStatement(int $id): string
    {
        if ($this->statementRepository->destroy($id)) {

            $this->statementEntityCache->forget($id);

            return ResponseMessages::DELETE_STATEMENT_SUCCESS;
        }

        return ErrorMessages::STATEMENT_NOT_FOUND;
    }

    /**
     * @throws InvalidStatusTransitionException|BindingResolutionException
     */
    public function transitionStatus(StatementDTO $dto, StatusTransitionType $type, ?User $actor = null): StatementDTO
    {
        $statement = $this->statementRepository->findById($dto->id);
        $strategy = $this->strategyResolver->resolve($type);

        if (!$strategy->canTransition($statement, $actor)) {
            throw new InvalidStatusTransitionException('Strategy can`t transit statement status');
        }

        $statement = $strategy->execute($statement, $actor);

        return $this->statementServiceDataAdapter->createResponseStatementDTO($statement);
    }
}
