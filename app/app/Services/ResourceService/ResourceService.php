<?php

namespace App\Services\ResourceService;

use App\Data\ResourceDTO\ResourceDTO;
use App\Models\Resource;
use App\Repositories\ResourceRepository\ResourceRepository;
use Illuminate\Database\Eloquent\Collection;

class ResourceService
{
    public function __construct(
        public ResourceRepository $resourceRepository,
    ) {}

    public function createResource(ResourceDTO $resourceDTO): Resource
    {
        return $this->resourceRepository->create($resourceDTO);
    }

    public function allResources(): Collection
    {
        return $this->resourceRepository->all();
    }
}
