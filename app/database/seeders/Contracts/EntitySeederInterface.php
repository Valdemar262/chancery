<?php

declare(strict_types=1);

namespace Database\Seeders\Contracts;

use App\Enums\EntitySeedType;

interface EntitySeederInterface
{
    public function run(int $count, bool $fresh): int;

    public static function supports(): EntitySeedType;
}
