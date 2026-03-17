<?php

namespace App\DataAdapters\BookingDataAdapter;

use App\Data\BookingDTO\BookingDTO;

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
        $resourceId,
        $userId,
        $startTime,
        $endTime
    ): BookingDTO {
        return BookingDTO::validateAndCreate([
            'resource_id' => $resourceId,
            'user_id' => $userId,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
    }
}
