<?php

declare(strict_types=1);

namespace App\Jobs\Handlers;

use App\Data\StatementNotificationPayload\StatementNotificationPayload;
use App\Enums\StatementNotificationType;
use App\Jobs\SendStatementSubmittedNotificationJob;
use App\Models\User;

class SubmittedNotificationHandler implements StatementNotificationHandlerInterface
{
    public function handle(StatementNotificationPayload $payload): void
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
