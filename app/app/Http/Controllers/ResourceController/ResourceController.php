<?php

namespace App\Http\Controllers\ResourceController;

use App\Exceptions\CollectionEmptyException;
use App\Services\ResourceService\ResourceService;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Http\JsonResponse;
use App\DataAdapters\ResourceDataAdapter\ResourceDataAdapter;
use App\Http\Controllers\Controller;
use Throwable;

class ResourceController extends Controller
{
    public function __construct(
        private readonly ResourceService $resourceService,
        private readonly ResourceDataAdapter $resourceDataAdapter
    ) {}

    public function createResources(Request $request): JsonResponse
    {
        return getSuccessResponse(
            $this->resourceService->createResource(
                $this->resourceDataAdapter->createResourceDTOByRequest($request),
            ),
        );
    }

    public function showResources(int $id): JsonResponse
    {
        return getSuccessResponse(
            $this->resourceService->showResources($id),
        );
    }

    /**
     * @throws Throwable
     */
    public function updateResource(Request $request): JsonResponse
    {
        return getSuccessResponse(
            $this->resourceService->updateResource(
                $this->resourceDataAdapter->createResourceDTOByRequest($request),
            ),
        );
    }

    public function deleteResource(int $id): JsonResponse
    {
        return getSuccessResponse(
            $this->resourceService->deleteResource($id),
        );
    }

    /**
     * @throws CollectionEmptyException
     */
    public function getAllResources(): JsonResponse
    {
        return getSuccessResponse(
            $this->resourceService->allResources(),
        );
    }
}
