<?php

declare(strict_types=1);

namespace App\Jobs\Handlers;

use App\Data\StatementNotificationPayload\StatementNotificationPayload;
use App\Enums\StatementNotificationType;
use App\Jobs\SendBookingCreatedNotificationJob;

class BookingCreatedNotificationHandler implements StatementNotificationHandlerInterface
{
    public function handle(StatementNotificationPayload $payload): void
    {
        SendBookingCreatedNotificationJob::dispatch(
            statement: $payload->statement,
            user: $payload->user,
            type: StatementNotificationType::BOOKING_CREATED,
        );
    }
}
