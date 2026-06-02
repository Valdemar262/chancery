<?php

declare(strict_types=1);

namespace App\Cache\Keys;

use App\Cache\Const\CacheNameConst;
use App\Cache\Helpers\CacheKeyHelper;

class StatementCacheKey
{
    public static function forId(int $id): string
    {
        return CacheKeyHelper::assembleKey(
            CacheNameConst::CACHE_RESOURCE_TYPE_ENTITY->value,
            CacheNameConst::CACHE_STATEMENT_KEY->value,
            $id,
        );
    }

    public static function forAll(): string
    {
        return CacheKeyHelper::assembleKey(
            CacheNameConst::CACHE_RESOURCE_TYPE_ENTITY->value,
            CacheNameConst::CACHE_STATEMENT_KEY->value,
            'all',
        );
    }
}
