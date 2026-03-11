<?php

namespace App\Services\StatementService;

use App\Data\AllStatementsDTO\AllStatementsDTO;
use App\Data\StatementDTO\StatementDTO;
use App\DataAdapters\StatementServiceDataAdapter\StatementServiceDataAdapter;
use App\Enums\ErrorMessages;
use App\Enums\ResponseMessages;
use App\Events\StatementSubmitted;
use App\Models\Statement;
use App\Repositories\StatementRepository\StatementRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Exception;
use Throwable;

class StatementService
{
    public function __construct(
        private readonly StatementServiceDataAdapter $statementServiceDataAdapter,
        private readonly StatementRepository         $statementRepository,
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
                'title' => $updateStatementDTO->title,
                'number' => $updateStatementDTO->number,
                'date' => $updateStatementDTO->date,
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
     * @throws Exception
     */
    public function submitStatement(StatementDTO $statementDTO): StatementDTO
    {
        try {
            $this->statementRepository->updateStatement(
                $this->statementServiceDataAdapter->createForSubmitStatementDTO($statementDTO),
            );

            event(new StatementSubmitted($this->statementRepository->findById($statementDTO->id)));

            return $this->statementServiceDataAdapter->createResponseStatementDTO(
                $this->statementRepository->findById($statementDTO->id)
            );
        } catch (Throwable $exception) {
            Log::error($exception);
            throw new Exception($exception->getMessage());
        }
    }
}
