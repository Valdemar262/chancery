<?php

declare(strict_types=1);

namespace App\Cache\Entities;

use Illuminate\Contracts\Cache\Repository;
use App\Cache\Keys\ResourceCacheKey;
use Illuminate\Support\Facades\Log;
use Throwable;

readonly class ResourceEntityCache
{
    public function __construct(
        private Repository $repository,
    ) {}

    public function get(int $id): ?array
    {
        try {
            $cacheResponse = $this->repository->get(ResourceCacheKey::forId($id));

            if (is_array($cacheResponse)) {
                return $cacheResponse;
            }

            return null;
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return null;
        }
    }

    public function getAll(): ?array
    {
        try {
            $cacheResponse = $this->repository->get(ResourceCacheKey::forAll());

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
        $this->repository->put(
            key: ResourceCacheKey::forId($id),
            value: $payload,
            ttl: config('entity_cache.entities.resource.ttl'),
        );
    }

    public function putAll(array $payload): void
    {
        $this->repository->put(
            key: ResourceCacheKey::forAll(),
            value: $payload,
            ttl: config('entity_cache.entities.resource.ttl'),
        );
    }

    public function forget(int $id): void
    {
        $this->repository->forget(ResourceCacheKey::forId($id));
    }

    public function forgetAll(): void
    {
        $this->repository->forget(ResourceCacheKey::forAll());
    }
}
