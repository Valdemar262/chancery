<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\StatementNotificationType;
use App\Jobs\Traits\LogExecutionTrait;
use App\Models\Statement;
use App\Models\User;
use App\Notifications\NotificationFactoryRegistry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SendStatementApprovedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LogExecutionTrait;

    public function __construct(
        public User $user,
        public int $statementId,
        public StatementNotificationType $type,
    ) {}

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(): void
    {
        $statement = Statement::query()->find($this->statementId);

        if (!$statement) {
            Log::error('Statement not found');
            return;
        }

        Log::info('Sending a letter to a user');

        $factory = app(NotificationFactoryRegistry::class)->get($this->type);

        $this->user->notify($factory->createNotification($statement));
    }
}
