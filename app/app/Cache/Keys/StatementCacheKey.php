<?php

declare(strict_types=1);

namespace App\Cache\Keys;

class StatementCacheKey
{
    public static function forId(int $id): string
    {
        return config('entity_cache.version', '1.0') . ':entity:' . 'statement:' . $id;
    }
}
