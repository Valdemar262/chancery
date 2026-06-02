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

class SendStatementSubmittedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LogExecutionTrait;

    public function __construct(
        public int $adminId,
        public int $statementId,
        public StatementNotificationType $type,
    ) {}

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(): void
    {
       Log::info('Sending email user for statement submitted');

       /** @var User $adminUser */
        $adminUser = User::query()->find($this->adminId);

        if (!$adminUser || !$adminUser->hasRole('admin')) {
            return;
        }

        $statement = Statement::query()->find($this->statementId);

        if (!$statement) {
            return;
        }

        $factory = app(NotificationFactoryRegistry::class)->get($this->type);

        $adminUser->notify($factory->createNotification($statement));
    }
}
