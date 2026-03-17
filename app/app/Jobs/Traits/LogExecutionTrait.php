<?php

declare(strict_types=1);

namespace App\Jobs\Traits;

use App\Queue\Middleware\LogJobExecution;

trait LogExecutionTrait
{
    public function middleware(): array
    {
        return [new LogJobExecution()];
    }
}
