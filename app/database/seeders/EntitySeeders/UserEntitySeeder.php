<?php

declare(strict_types=1);

namespace Database\Seeders\EntitySeeders;

use App\Enums\EntitySeedType;
use App\Models\User;
use Database\Seeders\Contracts\EntitySeederInterface;
use Illuminate\Support\Facades\Schema;

class UserEntitySeeder implements EntitySeederInterface
{
    public function run(int $count, bool $fresh): int
    {
        if ($fresh) {
            Schema::disableForeignKeyConstraints();
            User::query()->truncate();
            Schema::enableForeignKeyConstraints();
        }

        return count(User::factory()->count($count)->create());
    }

    public static function supports(): EntitySeedType
    {
        return EntitySeedType::USERS;
    }
}
