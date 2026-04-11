<?php

declare(strict_types=1);

namespace App\Repositories\BookingRepository;

use App\Models\Booking;
use App\Data\BookingDTO\BookingDTO;
use Illuminate\Database\Eloquent\Collection;

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

    public function getGroupBookingByResource(): Collection
    {
        return Booking::query()
            ->join('resources', 'bookings.resource_id', '=', 'resources.id')
            ->selectRaw('resource_id, resources.name as resource_name, count(*) as count')
            ->groupBy('resource_id', 'resources.name')
            ->get();
    }
}
