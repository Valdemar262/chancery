<?php

declare(strict_types=1);

namespace App\Jobs\Handlers;

use App\Data\StatementNotificationPayload\StatementNotificationPayload;

interface StatementNotificationHandlerInterface
{
    public function handle(StatementNotificationPayload $payload): void;
}
