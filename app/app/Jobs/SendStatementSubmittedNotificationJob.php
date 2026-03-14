<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Jobs\Traits\LogExecutionTrait;
use App\Models\Statement;
use App\Models\User;
use app\Notifications\StatementSubmittedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendStatementSubmittedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LogExecutionTrait;

    public function __construct(
        public int $adminId,
        public int $statementId,
    ) {}

    public function handle(): void
    {
       Log::info('Sending email user for statement submitted');

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
