<?php

declare(strict_types=1);

return [
    'version'  => env('CACHE_VERSION', '1.0'),
    'entities' => [
        'statement' => [
            'ttl' => (int) env('CACHE_TTL', 3600),
        ],
    ],
];
