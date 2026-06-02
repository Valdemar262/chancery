<?php

namespace App\DataAdapters\BookingDataAdapter;

use App\Data\BookingDTO\BookingDTO;
use App\Data\BookingDTO\BookingsByResourceDTO;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class BookingDataAdaptor
{
    public function createBookingDTOByRequest($request): BookingDTO
    {
        return $this->createBookingDTO(
            resourceId: $request->get('resource_id'),
            userId: $request->get('user_id'),
            startTime: $request->get('start_time'),
            endTime: $request->get('end_time'),
        );
    }

    public function createBookingDTO(
        int    $resourceId,
        int    $userId,
        string $startTime,
        string $endTime,
    ): BookingDTO {
        return BookingDTO::validateAndCreate([
            'resource_id' => $resourceId,
            'user_id'     => $userId,
            'start_time'  => $startTime,
            'end_time'    => $endTime,
        ]);
    }

    public function createBookingsByResourceDTOFromCollection(EloquentCollection $collection): Collection
    {
        return $collection->map(fn(object $row) => $this->createBookingsByResourceDTO(
            resourceId: (int)$row->resource_id,
            resourceName: $row->resource_name,
            count: (int)$row->count,
        ));
    }

    public function createBookingsByResourceDTO(int $resourceId, string $resourceName, int $count): BookingsByResourceDTO
    {
        return BookingsByResourceDTO::validateAndCreate([
            'resourceId'   => $resourceId,
            'resourceName' => $resourceName,
            'count'         => $count,
        ]);
    }
}
