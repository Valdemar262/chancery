<?php

declare(strict_types=1);

namespace App\Jobs\Handlers;

use App\Data\StatementNotificationPayload\StatementNotificationPayload;
use App\Enums\StatementNotificationType;
use App\Jobs\StatementRejectedNotificationJob;

class RejectedNotificationHandler implements StatementNotificationHandlerInterface
{
    public function handle(StatementNotificationPayload $payload): void
    {
        StatementRejectedNotificationJob::dispatch(
            statement: $payload->statement,
            user: $payload->user,
            admin: $payload->admin,
            type: StatementNotificationType::REJECTED,
        );
    }
}
