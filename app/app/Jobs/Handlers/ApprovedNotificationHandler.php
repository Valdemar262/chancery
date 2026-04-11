<?php

declare(strict_types=1);

namespace App\Jobs\Handlers;

use App\Data\StatementNotificationPayload\StatementNotificationPayload;
use App\Enums\StatementNotificationType;
use App\Jobs\SendStatementApprovedNotificationJob;

class ApprovedNotificationHandler implements StatementNotificationHandlerInterface
{
    public function handle(StatementNotificationPayload $payload): void
    {
        SendStatementApprovedNotificationJob::dispatch(
            user: $payload->user,
            statementId: $payload->statement->id,
            type: StatementNotificationType::APPROVED,
        );
    }
}
