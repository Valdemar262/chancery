<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Data\StatementNotificationPayload\StatementNotificationPayload;
use App\Enums\StatementNotificationType;
use App\Events\BookingConflict;
use App\Jobs\Dispatchers\StatementNotificationJobDispatcher;
use App\Jobs\Traits\LogExecutionTrait;
use App\Models\Booking;
use App\Models\Statement;
use App\Models\User;
use App\Services\BookingService\BookingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateBookingFromStatementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LogExecutionTrait;

    public function __construct(
        public Statement $statement,
        public User $user,
    ) {}

    public function handle(BookingService $bookingService): void
    {
        if ($bookingService->hasConflictProcessCreateBooking($this->statement)) {
            event(new BookingConflict($this->statement, $this->user));
            Log::info('Booking with this user_id and resource_id already created');
            return;
        }

        Booking::query()->create([
            'resource_id' => $this->statement->resource_id,
            'user_id'     => $this->statement->user_id,
            'start_time'  => $this->statement->date,
            'end_time'    => $this->statement->date,
        ]);

        app(StatementNotificationJobDispatcher::class)->dispatch(
            StatementNotificationType::BOOKING_CREATED,
            new StatementNotificationPayload($this->statement, $this->user),
        );
    }
}
