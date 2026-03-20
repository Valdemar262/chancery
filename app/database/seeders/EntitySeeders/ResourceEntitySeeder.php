<?php

declare(strict_types=1);

namespace Database\Seeders\EntitySeeders;

use App\Enums\EntitySeedType;
use App\Models\Resource;
use Database\Seeders\Contracts\EntitySeederInterface;
use Illuminate\Support\Facades\Schema;

class ResourceEntitySeeder implements EntitySeederInterface
{
    public function run(int $count, bool $fresh): int
    {
        if ($fresh) {
            Schema::disableForeignKeyConstraints();
            Resource::query()->truncate();
            Schema::enableForeignKeyConstraints();
        }

        return count(Resource::factory()->count($count)->create());
    }

    public static function supports(): EntitySeedType
    {
        return EntitySeedType::RESOURCES;
    }
}
