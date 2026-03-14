<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Statement;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingConflict
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Statement $statement,
        public User      $user,
    ) {}
}
