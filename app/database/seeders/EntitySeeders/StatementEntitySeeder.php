<?php

declare(strict_types=1);

namespace Database\Seeders\EntitySeeders;

use App\Enums\EntitySeedType;
use App\Models\Statement;
use Database\Seeders\Contracts\EntitySeederInterface;
use Illuminate\Support\Facades\Schema;

class StatementEntitySeeder implements EntitySeederInterface
{
    public function run(int $count, bool $fresh): int
    {
        if ($fresh) {
            Schema::disableForeignKeyConstraints();
            Statement::query()->truncate();
            Schema::enableForeignKeyConstraints();
        }

        return count(Statement::factory()->count($count)->create());
    }

    public static function supports(): EntitySeedType
    {
        return EntitySeedType::STATEMENTS;
    }
}
