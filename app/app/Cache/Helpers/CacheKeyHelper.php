<?php

declare(strict_types=1);

namespace App\Cache\Helpers;

class CacheKeyHelper
{
    public static function assembleKey(string $type, string $resource, int|string $param): string
    {
        return  config('entity_cache.version', '1.0') . ':' . $type . ':' . $resource . ':' . $param;
    }
}
