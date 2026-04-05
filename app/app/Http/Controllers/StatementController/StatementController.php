<?php

declare(strict_types=1);

namespace App\Http\Controllers\StatementController;

use App\DataAdapters\StatementServiceDataAdapter\StatementServiceDataAdapter;
use App\Enums\StatusTransitionType;
use App\Http\Controllers\Controller;
use App\Models\Statement;
use App\Services\StatementService\StatementService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class StatementController extends Controller
{
    public function __construct(
        private readonly StatementService            $statementService,
        private readonly StatementServiceDataAdapter $statementServiceDataAdapter,
    ) {}

    public function createStatement(Request $request): JsonResponse
    {
        return getSuccessResponse(
            $this->statementService->createStatement(
                $this->statementServiceDataAdapter->createStatementDTOByRequest($request),
            ));
    }

    public function getAllStatements(): JsonResponse
    {
        return getSuccessResponse(
            $this->statementService->allStatements(),
        );
    }

    public function showStatement(Statement $statement): JsonResponse
    {
        return getSuccessResponse(
            $this->statementServiceDataAdapter->createResponseStatementDTO($statement)
        );
    }

    public function updateStatement(Request $request): JsonResponse
    {
        return getSuccessResponse(
            $this->statementService->updateStatement(
                $this->statementServiceDataAdapter->createUpdateStatementDTOByRequest($request),
            ));
    }

    public function deleteStatement(int $id): JsonResponse
    {
        return getSuccessResponse($this->statementService->deleteStatement($id));
    }

    /**
     * @throws Throwable
     */
    public function submit(Statement $statement): JsonResponse
    {
        return getSuccessResponse(
            $this->statementService->transitionStatus(
                $this->statementServiceDataAdapter->createResponseStatementDTO($statement),
                StatusTransitionType::SUBMIT,
            ),
        );
    }

    /**
     * @throws Throwable
     */
    public function reject(Statement $statement): JsonResponse
    {
        return getSuccessResponse(
            $this->statementService->transitionStatus(
                $this->statementServiceDataAdapter->createResponseStatementDTO($statement),
                StatusTransitionType::REJECT,
                auth()->user(),
            ),
        );
    }

    /**
     * @throws Throwable
     */
    public function approve(Statement $statement): JsonResponse
    {
        return getSuccessResponse(
            $this->statementService->transitionStatus(
                $this->statementServiceDataAdapter->createResponseStatementDTO($statement),
                StatusTransitionType::APPROVE,
                auth()->user(),
            )
        );
    }
}
