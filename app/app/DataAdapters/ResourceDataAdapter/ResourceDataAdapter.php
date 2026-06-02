<?php

declare(strict_types=1);

namespace App\DataAdapters\ResourceDataAdapter;

use App\Data\ResourceDTO\ResourceDTO;
use App\Models\Resource;
use Symfony\Component\HttpFoundation\Request;

class ResourceDataAdapter
{
    public function createResourceDTOByRequest(Request $request): ResourceDTO
    {
        return $this->createResourceDTO(
            id: $request->get('id'),
            name: $request->get('name'),
            type: $request->get('type'),
            description: $request->get('description')
        );
    }

    public function createResourceDTO(
        ?int    $id,
        string  $name,
        string  $type,
        ?string $description
    ): ResourceDTO {
        return ResourceDTO::validateAndCreate([
            'id'          => $id,
            'name'        => $name,
            'type'        => $type,
            'description' => $description
        ]);
    }

    public function createResponseResourceDTO(Resource $resource): ResourceDTO
    {
        return ResourceDTO::validateAndCreate([
            'id'          => $resource->id,
            'name'        => $resource->name,
            'type'        => $resource->type,
            'description' => $resource->description,
        ]);
    }

    public function createResourceDTOByArray(array $data): ResourceDTO
    {
        return ResourceDTO::validateAndCreate($data);
    }
}
