<?php

namespace App\Http\Controllers\ResourceController;

use App\Services\ResourceService\ResourceService;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Http\JsonResponse;
use App\DataAdapters\ResourceDataAdapter\ResourceDataAdapter;

class ResourceController
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
            )
        );
    }

    public function getAllResources(): JsonResponse
    {
        return getSuccessResponse(
            $this->resourceService->allResources()
        );
    }
}
