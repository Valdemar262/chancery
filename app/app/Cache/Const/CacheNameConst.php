<?php

declare(strict_types=1);

namespace App\Cache\Const;

enum CacheNameConst: string
{
    case CACHE_RESOURCE_TYPE_ENTITY = 'entity';
    case CACHE_RESOURCE_KEY = 'resource';
    case CACHE_STATEMENT_KEY = 'statement';
}
