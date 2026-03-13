<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Statement;
use App\Models\User;
use App\Notifications\StatementRejectedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StatementRejectedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Statement $statement,
        public User      $user,
        public User      $admin,
    ) {}

    public function handle(): void
    {
        Log::info('Sending email user for statement rejected');

        $this->user->notify(new StatementRejectedNotification($this->statement, $this->user, $this->admin));
    }
}
