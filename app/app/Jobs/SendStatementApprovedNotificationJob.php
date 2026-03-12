<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Statement;
use App\Models\User;
use App\Notifications\StatementApprovedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendStatementApprovedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public int $statementId,
    ) {}

    public function handle(): void
    {
        $statement = Statement::query()->find($this->statementId);

        if (!$statement) {
            Log::error('Statement not found');
            return;
        }

        Log::info('Sending a letter to a user');

        $this->user->notify(new StatementApprovedNotification($statement));
    }
}
