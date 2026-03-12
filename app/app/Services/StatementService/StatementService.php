<?php

namespace App\Services\StatementService;

use App\Data\AllStatementsDTO\AllStatementsDTO;
use App\Data\StatementDTO\StatementDTO;
use App\DataAdapters\StatementServiceDataAdapter\StatementServiceDataAdapter;
use App\Enums\ErrorMessages;
use App\Enums\ResponseMessages;
use app\Enums\StatementStatus;
use App\Events\StatementApproved;
use App\Events\StatementSubmitted;
use App\Exceptions\ForbiddenException;
use App\Models\Resource;
use App\Models\Statement;
use App\Models\User;
use App\Repositories\StatementRepository\StatementRepository;
use App\Services\RoleAndPermissionService\RoleAndPermissionChecker;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Throwable;

class StatementService
{
    public function __construct(
        private readonly StatementServiceDataAdapter $statementServiceDataAdapter,
        private readonly StatementRepository         $statementRepository,
        private readonly RoleAndPermissionChecker    $roleAndPermissionChecker,
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

    /**
     * @throws ForbiddenException
     * @throws Throwable
     */
    public function approveStatement(StatementDTO $statementDTO, Authenticatable|User $admin): StatementDTO
    {
        $this->roleAndPermissionChecker->hasAdminRole($admin);

        Log::info('User pass verification role admin');

        try {
            $statement = $this->statementRepository->findById($statementDTO->id);

            $resource = Resource::query()
                ->where('name', '=', $statement->getAttribute('title'))
                ->first();

            if (!$resource) {
                throw new ModelNotFoundException(
                    ErrorMessages::RESOURCE_NOT_FOUND . ' with the name ' . $statement->getAttribute('title')
                );
            }

            Statement::query()->find($statementDTO->id)
                ->update([
                    'resource_id' => $resource->id,
                    'status'      => StatementStatus::APPROVED->value,
                    'approved_by' => $admin->id,
                ]);

            $updatedStatement = $this->statementRepository->findById($statementDTO->id);

            $user = User::query()->find($statement->user_id);

            event(new StatementApproved($updatedStatement, $user));

            return $this->statementServiceDataAdapter->createResponseStatementDTO($updatedStatement);
        } catch (Throwable $exception) {
            Log::error($exception);
            return throw $exception;
        }
    }

//    public function rejectStatement(StatementDTO $statementDTO): StatementDTO
//    {
//        try {
//            $this->statementRepository->updateStatement();
//        }
//    }
}
