<?php

namespace App\Repositories\ResourceRepository;

use App\Data\ResourceDTO\ResourceDTO;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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

    public function findByField(string $field, string|int $value): Resource|Model|null
    {
        return Resource::query()->where($field, '=', $value)->first();
    }
}
