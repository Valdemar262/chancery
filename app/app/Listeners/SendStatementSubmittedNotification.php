<?php

namespace App\Listeners;

use App\Events\StatementSubmitted;
use App\Jobs\SendStatementSubmittedNotificationJob;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SendStatementSubmittedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(StatementSubmitted $event): void
    {
        Log::info('Received the event of sending a request for review', [
            'statement_id' => $event->statement->id,
        ]);

        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            SendStatementSubmittedNotificationJob::dispatch(
                adminId: $admin->id,
                statementId: $event->statement->id,
            );
        }
    }
}
