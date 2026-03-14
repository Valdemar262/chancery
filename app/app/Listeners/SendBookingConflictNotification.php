<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\BookingConflict;
use App\Jobs\BookingConflictNotificationJob;
use Illuminate\Support\Facades\Log;

class SendBookingConflictNotification
{
    public function handle(BookingConflict $event): void
    {
        Log::info('Listener BookingConflict processing');

        BookingConflictNotificationJob::dispatch(
            $event->statement->getAttribute('id'),
            $event->user->getAttribute('id'),
        );
    }
}
