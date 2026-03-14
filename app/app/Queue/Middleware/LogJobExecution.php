<?php

declare(strict_types=1);

namespace App\Queue\Middleware;

use Illuminate\Support\Facades\Log;

class LogJobExecution
{
    public function handle(object $job, callable $next): mixed
    {
        Log::info('Job execution started', [
            'job' => get_class($job),
            'started_at' => now()->toIso8601String(),
            'memory' => memory_get_usage(true),
        ]);

        $result = $next($job);

        Log::info('Job execution finished', [
            'job' => get_class($job),
            'memory' => memory_get_peak_usage(true),
            'finished_at' => now()->toIso8601String(),
        ]);

        return $result;
    }
}
