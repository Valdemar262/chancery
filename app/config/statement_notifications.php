<?php

declare(strict_types=1);

use App\Enums\StatementNotificationType;
use App\Jobs\Handlers\ApprovedNotificationHandler;
use App\Jobs\Handlers\BookingConflictNotificationHandler;
use App\Jobs\Handlers\BookingCreatedNotificationHandler;
use App\Jobs\Handlers\RejectedNotificationHandler;
use App\Jobs\Handlers\SubmittedNotificationHandler;

return [
    StatementNotificationType::SUBMITTED->value        => SubmittedNotificationHandler::class,
    StatementNotificationType::APPROVED->value         => ApprovedNotificationHandler::class,
    StatementNotificationType::REJECTED->value         => RejectedNotificationHandler::class,
    StatementNotificationType::BOOKING_CONFLICT->value => BookingConflictNotificationHandler::class,
    StatementNotificationType::BOOKING_CREATED->value  => BookingCreatedNotificationHandler::class,
];
