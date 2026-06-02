<?php

declare(strict_types=1);

namespace App\Services\ResourceService;

use App\Cache\Entities\ResourceEntityCache;
use App\Data\ResourceDTO\ResourceDTO;
use App\DataAdapters\ResourceDataAdapter\ResourceDataAdapter;
use App\Enums\ErrorMessages;
use App\Enums\ResponseMessages;
use App\Exceptions\CollectionEmptyException;
use App\Exceptions\ServerException;
use App\Models\Resource;
use App\Repositories\ResourceRepository\ResourceRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Throwable;

readonly class ResourceService
{
    public function __construct(
        private ResourceRepository  $resourceRepository,
        private ResourceEntityCache $resourceEntityCache,
        private ResourceDataAdapter $resourceDataAdapter,
    ) {}

    public function createResource(ResourceDTO $resourceDTO): Resource
    {
        $resource = $this->resourceRepository->create($resourceDTO);
        $resourceDTO = $this->resourceDataAdapter->createResponseResourceDTO($resource);
        $this->resourceEntityCache->put($resourceDTO->id, $resourceDTO->toArray());

        return $resource;
    }

    public function showResources(int $id): ResourceDTO
    {
        $cacheResource = $this->resourceEntityCache->get($id);

        if (!$cacheResource) {
            $resource = $this->resourceRepository->findById($id);

            if (!$resource) {
                throw new ModelNotFoundException(ErrorMessages::RESOURCE_NOT_FOUND);
            }

            $resourceDTO = $this->resourceDataAdapter->createResponseResourceDTO($resource);
            $this->resourceEntityCache->put($id, $resourceDTO->toArray());
            return $resourceDTO;
        }

        return $this->resourceDataAdapter->createResourceDTOByArray($cacheResource);
    }

    /**
     * @throws Throwable
     */
    public function updateResource(ResourceDTO $resourceDTO): ResourceDTO
    {
        try {
            $resource = $this->resourceRepository->findById($resourceDTO->id);

            if (!$resource) {
                throw new ModelNotFoundException(ErrorMessages::RESOURCE_NOT_FOUND);
            }

            $this->resourceRepository->update(
                resource: $resource,
                data: Arr::only(
                    $resourceDTO->toArray(),
                    ['name', 'type', 'description'],
                ),
            );

            $resourceDTO = $this->resourceDataAdapter->createResponseResourceDTO($resource);
            $this->resourceEntityCache->put($resourceDTO->id, $resourceDTO->toArray());
            return $resourceDTO;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            throw new ServerException(ErrorMessages::SERVER_ERROR);
        }
    }

    public function deleteResource(int $id): string
    {
        if ($this->resourceRepository->destroy($id)) {
            return ResponseMessages::DELETE_RESOURCE_SUCCESS;
        }

        return ErrorMessages::RESOURCE_NOT_FOUND;
    }

    /**
     * @throws CollectionEmptyException
     */
    public function allResources(): Collection
    {
        $allResourcesCache = $this->resourceEntityCache->getAll();

        if (!$allResourcesCache) {
            $allResources = $this->resourceRepository->getAll();

            if ($allResources->isEmpty()) {
                throw new CollectionEmptyException(ErrorMessages::RESOURCE_COLLECTION_EMPTY);
            }

            $this->resourceEntityCache->putAll($allResources->toArray());
            return $allResources;
        }

        return Resource::hydrate($allResourcesCache);
    }
}
