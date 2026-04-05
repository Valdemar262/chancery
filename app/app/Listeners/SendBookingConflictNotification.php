<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Data\StatementNotificationPayload\StatementNotificationPayload;
use App\Enums\StatementNotificationType;
use App\Events\BookingConflict;
use App\Exceptions\NotFoundException;
use App\Jobs\Dispatchers\StatementNotificationJobDispatcher;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;

class SendBookingConflictNotification
{
    /**
     * @throws BindingResolutionException
     * @throws NotFoundException
     */
    public function handle(BookingConflict $event): void
    {
        Log::info('Listener BookingConflict processing');

        app(StatementNotificationJobDispatcher::class)->dispatch(
            StatementNotificationType::BOOKING_CONFLICT,
            new StatementNotificationPayload($event->statement, $event->user),
        );
    }
}
