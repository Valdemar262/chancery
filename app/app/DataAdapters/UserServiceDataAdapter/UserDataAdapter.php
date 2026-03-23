<?php

declare(strict_types=1);

namespace App\DataAdapters\UserServiceDataAdapter;

use App\Data\UserDTO\UserActivityDTO;
use Illuminate\Support\Collection;

class UserDataAdapter
{
    public function createUserActivityDTOByObject(Collection $collection): Collection
    {
        return $collection->map(fn(object $row) => $this->createUserActivityDTO(
            userId: (int)$row->user_id,
            userName: (string)$row->user_name,
            userEmail: (string)$row->user_email,
            statementsCount: (int)$row->statements_count,
            bookingsCount: (int)$row->bookings_count,
        ));
    }

    public function createUserActivityDTO(
        int    $userId,
        string $userName,
        string $userEmail,
        int    $statementsCount,
        int    $bookingsCount,
    ): UserActivityDTO {
        return UserActivityDTO::validateAndCreate([
            'userId'          => $userId,
            'userName'        => $userName,
            'userEmail'       => $userEmail,
            'statementsCount' => $statementsCount,
            'bookingsCount'   => $bookingsCount,
        ]);
    }
}
