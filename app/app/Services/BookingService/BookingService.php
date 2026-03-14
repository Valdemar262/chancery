<?php

declare(strict_types=1);

namespace App\Services\BookingService;

use App\Data\BookingDTO\BookingDTO;
use App\Models\Booking;
use App\Enums\ResponseMessages;
use App\Models\Statement;
use App\Repositories\BookingRepository\BookingRepository;
use App\Repositories\ResourceRepository\ResourceRepository;

class BookingService
{
    public function __construct(
        private readonly BookingRepository  $bookingRepository,
        private readonly ResourceRepository $resourceRepository,
    ) {}

    public function createBooking(BookingDTO $bookingDTO): Booking
    {
        return $this->bookingRepository->create($bookingDTO);
    }

    public function getBookings(int $id)
    {
        return $this->resourceRepository->getResourceById($id)->bookings;
    }

    public function deleteBookings(int $id): string
    {
        if ($this->bookingRepository->delete($id)) {
            return ResponseMessages::DELETE_BOOKING_SUCCESS;
        }

        return ResponseMessages::BOOKING_NOT_FOUND;
    }

    public function hasConflictProcessCreateBooking(Statement $statement): bool
    {
        return Booking::query()->where([
            'user_id'     => $statement->user_id,
            'resource_id' => $statement->resource_id
        ])->exists();
    }
}
