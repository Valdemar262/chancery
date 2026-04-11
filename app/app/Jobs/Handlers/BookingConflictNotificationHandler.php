<?php

declare(strict_types=1);

namespace App\Jobs\Handlers;

use App\Data\StatementNotificationPayload\StatementNotificationPayload;
use App\Enums\StatementNotificationType;
use App\Jobs\BookingConflictNotificationJob;

class BookingConflictNotificationHandler implements StatementNotificationHandlerInterface
{
    public function handle(StatementNotificationPayload $payload): void
    {
        BookingConflictNotificationJob::dispatch(
            statementId: $payload->statement->id,
            userId: $payload->user->id,
            type: StatementNotificationType::BOOKING_CONFLICT,
        );
    }
}
