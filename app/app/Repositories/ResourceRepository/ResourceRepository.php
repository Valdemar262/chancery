<?php

namespace App\Repositories\ResourceRepository;

use App\Data\ResourceDTO\ResourceDTO;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Collection;

class ResourceRepository
{
    public function create(ResourceDTO $resourceDTO): Resource
    {
        return Resource::create($resourceDTO->toArray());
    }

    public function all(): Collection
    {
        return Resource::all();
    }

    public function getResourceById(int $id): Resource
    {
        return Resource::findOrFail($id);
    }
}
