<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\StatementApproved;
use App\Jobs\CreateBookingFromStatementJob;
use Illuminate\Support\Facades\Log;

class CreateBookingFromStatement
{
    public function handle(StatementApproved $event): void
    {
        Log::info('Starting create booking from statement.', [
            'statement_id' => $event->statement->id,
            'resource_id'  => $event->statement->resource_id,
            'status'       => $event->statement->status,
        ]);

        CreateBookingFromStatementJob::dispatch($event->statement, $event->user);
    }
}
