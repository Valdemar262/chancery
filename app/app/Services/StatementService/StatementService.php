<?php

declare (strict_types = 1);

namespace App\Services\StatementService;

use App\Data\AllStatementsDTO\AllStatementsDTO;
use App\Data\StatementDTO\StatementDTO;
use App\DataAdapters\StatementServiceDataAdapter\StatementServiceDataAdapter;
use App\Enums\ErrorMessages;
use App\Enums\ResponseMessages;
use App\Enums\StatusTransitionType;
use App\Exceptions\InvalidStatusTransitionException;
use App\Models\Statement;
use App\Models\User;
use App\Repositories\StatementRepository\StatementRepository;
use App\Services\StatementService\Strategies\StatusTransitionStrategyResolver;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

readonly class StatementService
{
    public function __construct(
        private StatementServiceDataAdapter      $statementServiceDataAdapter,
        private StatementRepository              $statementRepository,
        private StatusTransitionStrategyResolver $strategyResolver,
    ) {}

    public function createStatement(StatementDTO $statementDTO): Statement
    {
        return Statement::create($statementDTO->toArray());
    }

    public function allStatements(): AllStatementsDTO
    {
        return $this->statementServiceDataAdapter->createAllStatementsDTO(
            $this->statementRepository->getAll(),
        );
    }

    public function updateStatement(StatementDTO $updateStatementDTO): StatementDTO
    {
        try {
            $statement = $this->statementRepository->findById($updateStatementDTO->id);

            $statement->update([
                'title'  => $updateStatementDTO->title,
                'number' => $updateStatementDTO->number,
                'date'   => $updateStatementDTO->date,
            ]);

            return $this->statementServiceDataAdapter->createResponseStatementDTO($statement);
        } catch (Throwable) {
            throw new ModelNotFoundException(ErrorMessages::STATEMENT_NOT_FOUND);
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
     * @throws InvalidStatusTransitionException
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
