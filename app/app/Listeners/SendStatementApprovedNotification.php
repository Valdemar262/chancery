<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\StatementApproved;
use App\Jobs\SendStatementApprovedNotificationJob;
use Illuminate\Support\Facades\Log;

class SendStatementApprovedNotification
{
    public function handle(StatementApproved $event): void
    {
        Log::info('The admin has checked the application.', [
            'statement_id' => $event->statement->id,
            'admin_id'     => $event->user->id,
        ]);

        SendStatementApprovedNotificationJob::dispatch(
            user: $event->user,
            statementId: $event->statement->id,
        );
    }
}
