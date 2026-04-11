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
use App\Exceptions\CollectionEmptyException;
use App\Exceptions\InvalidStatusTransitionException;
use App\Exceptions\ServerException;
use App\Models\Statement;
use App\Models\User;
use App\Repositories\StatementRepository\StatementRepository;
use App\Services\StatementService\Strategies\StatusTransitionStrategyResolver;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
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

        if (!$cacheStatement) {
            $statement = $this->statementRepository->findById($id);

            if (!$statement) {
                throw new ModelNotFoundException(ErrorMessages::STATEMENT_NOT_FOUND);
            }

            $statementDTO = $this->statementServiceDataAdapter->createResponseStatementDTO($statement);
            $this->statementEntityCache->put($id, $statementDTO->toArray());
            return $statementDTO;
        }

        return $this->statementServiceDataAdapter->createStatementDTOByArray($cacheStatement);
    }

    /**
     * @throws CollectionEmptyException
     */
    public function allStatements(): AllStatementsDTO
    {
        $allStatementsCache = $this->statementEntityCache->getAll();

        if (!$allStatementsCache) {
            $allStatements = $this->statementRepository->getAll();

            if ($allStatements->isEmpty()) {
                throw new CollectionEmptyException(ErrorMessages::STATEMENT_COLLECTION_EMPTY);
            }

            $this->statementEntityCache->putAll($allStatements->toArray());
            return $this->statementServiceDataAdapter->createAllStatementsDTO($allStatements);
        }

        return $this->statementServiceDataAdapter->createAllStatementsDTO(Statement::hydrate($allStatementsCache));
    }

    /**
     * @throws ServerException
     */
    public function updateStatement(StatementDTO $updateStatementDTO): StatementDTO
    {
        try {
            $statement = $this->statementRepository->findById($updateStatementDTO->id);

            if ($statement === null) {
                throw new ModelNotFoundException(ErrorMessages::STATEMENT_NOT_FOUND);
            }

            $this->statementRepository->update(
                statement: $statement,
                data: Arr::only(
                    $updateStatementDTO->toArray(),
                    ['title', 'number', 'date'],
                ),
            );

            $statementDTO = $this->statementServiceDataAdapter->createResponseStatementDTO($statement);

            $this->statementEntityCache->put(
                id: $updateStatementDTO->id,
                payload: $statementDTO->toArray(),
            );

            return $statementDTO;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            throw new ServerException(ErrorMessages::SERVER_ERROR);
        }
    }

    public function deleteStatement(int $id): string
    {
        if ($this->statementRepository->destroy($id)) {
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

        if (!$statement) {
            throw new ModelNotFoundException(ErrorMessages::STATEMENT_NOT_FOUND);
        }

        $strategy = $this->strategyResolver->resolve($type);

        if (!$strategy->canTransition($statement, $actor)) {
            throw new InvalidStatusTransitionException('Strategy can`t transit statement status');
        }

        $statement = $strategy->execute($statement, $actor);

        return $this->statementServiceDataAdapter->createResponseStatementDTO($statement);
    }
}
