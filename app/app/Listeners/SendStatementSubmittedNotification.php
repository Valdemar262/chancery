<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Data\StatementNotificationPayload\StatementNotificationPayload;
use App\Enums\StatementNotificationType;
use App\Events\StatementSubmitted;
use App\Exceptions\NotFoundException;
use App\Jobs\Dispatchers\StatementNotificationJobDispatcher;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;

class SendStatementSubmittedNotification
{
    /**
     * @throws BindingResolutionException
     * @throws NotFoundException
     */
    public function handle(StatementSubmitted $event): void
    {
        Log::info('Received the event of sending a request for review', [
            'statement_id' => $event->statement->id,
        ]);

        app(StatementNotificationJobDispatcher::class)->dispatch(
            StatementNotificationType::SUBMITTED,
            new StatementNotificationPayload($event->statement),
        );
    }
}
