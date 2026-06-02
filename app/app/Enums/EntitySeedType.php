<?php

declare(strict_types=1);

namespace App\Enums;

enum EntitySeedType: string
{
    case USERS = 'users';
    case STATEMENTS = 'statements';
    case RESOURCES = 'resources';
    case BOOKINGS = 'bookings';
}
