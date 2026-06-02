<?php

declare(strict_types=1);

namespace Database\Seeders\EntitySeeders;

use App\Enums\EntitySeedType;
use App\Models\Booking;
use Database\Seeders\Contracts\EntitySeederInterface;
use Illuminate\Support\Facades\Schema;

class BookingEntitySeeder implements EntitySeederInterface
{
    public function run(int $count, bool $fresh): int
    {
        if ($fresh) {
            Schema::disableForeignKeyConstraints();
            Booking::query()->truncate();
            Schema::enableForeignKeyConstraints();
        }

        return count(Booking::factory()->count($count)->create());
    }

    public static function supports(): EntitySeedType
    {
        return EntitySeedType::BOOKINGS;
    }
}
