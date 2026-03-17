<?php

declare(strict_types=1);

namespace App\Jobs\Dispatchers;

use App\Data\StatementNotificationPayload\StatementNotificationPayload;
use App\Enums\StatementNotificationType;
use App\Jobs\BookingConflictNotificationJob;
use App\Jobs\SendBookingCreatedNotificationJob;
use App\Jobs\SendStatementApprovedNotificationJob;
use App\Jobs\SendStatementSubmittedNotificationJob;
use App\Jobs\StatementRejectedNotificationJob;
use App\Models\User;

class StatementNotificationJobDispatcher
{
    public function dispatch(StatementNotificationType $type, StatementNotificationPayload $payload): void
    {
        match ($type) {
            StatementNotificationType::SUBMITTED => $this->dispatchToAdmins($payload),
            StatementNotificationType::APPROVED => SendStatementApprovedNotificationJob::dispatch(
                user: $payload->user,
                statementId: $payload->statement->id,
                type: StatementNotificationType::APPROVED,
            ),
            StatementNotificationType::REJECTED => StatementRejectedNotificationJob::dispatch(
                statement: $payload->statement,
                user: $payload->user,
                admin: $payload->admin,
                type: StatementNotificationType::REJECTED,
            ),
            StatementNotificationType::BOOKING_CONFLICT => BookingConflictNotificationJob::dispatch(
                statementId: $payload->statement->id,
                userId: $payload->user->id,
                type: StatementNotificationType::BOOKING_CONFLICT,
            ),
            StatementNotificationType::BOOKING_CREATED => SendBookingCreatedNotificationJob::dispatch(
                statement: $payload->statement,
                user: $payload->user,
                type: StatementNotificationType::BOOKING_CREATED,
            ),
        };
    }

    public function dispatchToAdmins(StatementNotificationPayload $payload): void
    {
        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            SendStatementSubmittedNotificationJob::dispatch(
                adminId: $admin->id,
                statementId: $payload->statement->id,
                type: StatementNotificationType::SUBMITTED,
            );
        }
    }
}
