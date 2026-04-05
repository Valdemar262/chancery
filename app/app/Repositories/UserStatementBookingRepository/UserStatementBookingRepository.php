<?php

declare(strict_types=1);

namespace App\Repositories\UserStatementBookingRepository;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class UserStatementBookingRepository
{
    public function getUserActivityByStatementForBooking(): Collection
    {
        return DB::table('users')
            ->leftJoin('statements', 'statements.user_id', '=', 'users.id')
            ->leftJoin('bookings', 'bookings.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                DB::raw('COUNT(DISTINCT statements.id) as statements_count'),
                DB::raw('COUNT(DISTINCT bookings.id) as bookings_count')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByRaw('COUNT(DISTINCT statements.id) + COUNT(DISTINCT bookings.id) DESC')
            ->get();
    }
}
