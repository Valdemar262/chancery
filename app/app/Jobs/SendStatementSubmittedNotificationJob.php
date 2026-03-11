<?php

namespace App\Jobs;

use App\Models\Statement;
use App\Models\User;
use app\Notifications\StatementSubmittedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendStatementSubmittedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $adminId,
        public int $statementId,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $adminUser = User::query()->find($this->adminId);

        if (!$adminUser || !$adminUser->hasRole('admin')) {
            return;
        }

        $statement = Statement::query()->find($this->statementId);

        if (!$statement) {
            return;
        }

        $adminUser->notify(new StatementSubmittedNotification($statement));
    }
}
