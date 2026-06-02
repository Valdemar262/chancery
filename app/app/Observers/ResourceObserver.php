<?php

declare(strict_types=1);

namespace App\Observers;

use App\Cache\Entities\ResourceEntityCache;
use App\Models\Resource;

readonly class ResourceObserver
{
    public function __construct(
        private ResourceEntityCache $resourceEntityCache,
    ) {}

    public function created(Resource $resource): void
    {
        $this->resourceEntityCache->forgetAll();
    }

    public function updated(Resource $resource): void
    {
        $this->resourceEntityCache->forget($resource->id);
        $this->resourceEntityCache->forgetAll();
    }

    public function deleted(Resource $resource): void
    {
        $this->resourceEntityCache->forget($resource->id);
        $this->resourceEntityCache->forgetAll();
    }

    public function restored(Resource $resource): void
    {
        //
    }

    public function forceDeleted(Resource $resource): void
    {
        //
    }
}
