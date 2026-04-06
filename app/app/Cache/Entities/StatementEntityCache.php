<?php

declare(strict_types=1);

namespace App\Cache\Entities;

use App\Cache\Keys\StatementCacheKey;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Log;
use Throwable;

readonly class StatementEntityCache
{
    public function __construct(
        private Repository $repository,
    ) {}

    public function get(int $id): ?array
    {
        try {
            $cacheResponse = $this->repository->get(StatementCacheKey::forId($id));

            if (is_array($cacheResponse)) {
                return $cacheResponse;
            }

            return null;

        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return null;
        }
    }

    public function put(int $id, array $payload): void
    {
        $this->repository->put(StatementCacheKey::forId($id), $payload, config('entity_cache.entities.statement.ttl'));
    }

    public function forget(int $id): void
    {
        $this->repository->forget(StatementCacheKey::forId($id));
    }
}
