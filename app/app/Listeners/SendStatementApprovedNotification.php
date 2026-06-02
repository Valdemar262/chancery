<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Data\StatementNotificationPayload\StatementNotificationPayload;
use App\Enums\StatementNotificationType;
use App\Events\StatementApproved;
use App\Exceptions\NotFoundException;
use App\Jobs\Dispatchers\StatementNotificationJobDispatcher;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;

class SendStatementApprovedNotification
{
    /**
     * @throws BindingResolutionException
     * @throws NotFoundException
     */
    public function handle(StatementApproved $event): void
    {
        Log::info('The admin has checked the application.', [
            'statement_id' => $event->statement->id,
            'user_id'      => $event->user->id,
        ]);

        app(StatementNotificationJobDispatcher::class)->dispatch(
            StatementNotificationType::APPROVED,
            new StatementNotificationPayload($event->statement, $event->user)
        );
    }
}
