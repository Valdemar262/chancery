<?php

namespace App\Http\Controllers\StatementController;

use App\DataAdapters\StatementServiceDataAdapter\StatementServiceDataAdapter;
use App\Http\Controllers\Controller;
use App\Models\Statement;
use App\Services\StatementService\StatementService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
}
