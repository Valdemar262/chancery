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

class CreateBookingFromStatement
{
    /**
     * @throws BindingResolutionException
     * @throws NotFoundException
     */
    public function handle(StatementApproved $event): void
    {
        Log::info('Starting create booking from statement.', [
            'statement_id' => $event->statement->id,
            'resource_id'  => $event->statement->resource_id,
            'status'       => $event->statement->status,
        ]);

        app(StatementNotificationJobDispatcher::class)->dispatch(
            StatementNotificationType::BOOKING_CREATED,
            new StatementNotificationPayload($event->statement, $event->user),
        );
    }
}
