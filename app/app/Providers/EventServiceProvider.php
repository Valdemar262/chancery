<?php

namespace App\Providers;

use App\Events\BookingConflict;
use App\Events\StatementApproved;
use App\Events\StatementRejected;
use App\Events\StatementSubmitted;
use App\Listeners\CreateBookingFromStatement;
use App\Listeners\SendBookingConflictNotification;
use App\Listeners\SendStatementApprovedNotification;
use App\Listeners\SendStatementRejectNotification;
use App\Listeners\SendStatementSubmittedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        StatementSubmitted::class => [
            SendStatementSubmittedNotification::class,
        ],
        StatementApproved::class => [
            SendStatementApprovedNotification::class,
            CreateBookingFromStatement::class,
        ],
        StatementRejected::class => [
            SendStatementRejectNotification::class,
        ],
        BookingConflict::class => [
            SendBookingConflictNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
