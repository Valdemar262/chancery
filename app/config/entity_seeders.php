<?php

declare(strict_types=1);

use Database\Seeders\EntitySeeders\ResourceEntitySeeder;
use Database\Seeders\EntitySeeders\StatementEntitySeeder;
use Database\Seeders\EntitySeeders\UserEntitySeeder;
use Database\Seeders\EntitySeeders\BookingEntitySeeder;

return [
    UserEntitySeeder::class,
    StatementEntitySeeder::class,
    ResourceEntitySeeder::class,
    BookingEntitySeeder::class,
];
