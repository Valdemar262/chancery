<?php

declare(strict_types=1);

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

    public function getAll(): Collection
    {
        return Resource::all();
    }

    public function findById(int $id): ?Resource
    {
        return Resource::find($id);
    }

    public function findByField(string $field, string|int $value): Resource|Model|null
    {
        return Resource::query()->where($field, '=', $value)->first();
    }

    public function update(Resource $resource, array $data): bool
    {
        return $resource->update($data);
    }

    public function destroy(int $id): int
    {
        return Resource::destroy($id);
    }
}
