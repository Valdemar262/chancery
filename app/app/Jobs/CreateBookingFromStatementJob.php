<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Booking;
use App\Models\Statement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateBookingFromStatementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Statement $statement,
    ) {}

    public function handle(): void
    {
        Booking::query()->create([
            'resource_id' => $this->statement->resource_id,
            'user_id' => $this->statement->user_id,
            'start_time' => $this->statement->date,
            'end_time' => $this->statement->date,
        ]);
    }
}
