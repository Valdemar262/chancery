<?php

namespace App\Repositories\BookingRepository;

use App\Models\Booking;
use App\Data\BookingDTO\BookingDTO;

class BookingRepository
{
    public function create(BookingDTO $bookingDTO): Booking
    {
        return Booking::create($bookingDTO->toArray());
    }

    public function delete(int $id): int
    {
        return Booking::destroy($id);
    }
}
