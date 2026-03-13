<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\StatementRejected;
use App\Jobs\StatementRejectedNotificationJob;
use Illuminate\Support\Facades\Log;

class SendStatementRejectNotification
{
    public function handle(StatementRejected $event): void
    {
        Log::info('The admin has rejected statement', [
            'statement' => $event->statement,
            'user_id'   => $event->user->id,
            'admin_id'   => $event->admin->id,
        ]);

        StatementRejectedNotificationJob::dispatch(
            statement: $event->statement,
            user: $event->user,
            admin: $event->admin,
        );
    }
}
