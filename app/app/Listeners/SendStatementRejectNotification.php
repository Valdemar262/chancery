<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Data\StatementNotificationPayload\StatementNotificationPayload;
use App\Enums\StatementNotificationType;
use App\Events\StatementRejected;
use App\Exceptions\NotFoundException;
use App\Jobs\Dispatchers\StatementNotificationJobDispatcher;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;

class SendStatementRejectNotification
{
    /**
     * @throws BindingResolutionException
     * @throws NotFoundException
     */
    public function handle(StatementRejected $event): void
    {
        Log::info('The admin has rejected statement', [
            'statement' => $event->statement,
            'user_id'   => $event->user->id,
            'admin_id'  => $event->admin->id,
        ]);

        app(StatementNotificationJobDispatcher::class)->dispatch(
            StatementNotificationType::REJECTED,
            new StatementNotificationPayload($event->statement, $event->user, $event->admin),
        );
    }
}
