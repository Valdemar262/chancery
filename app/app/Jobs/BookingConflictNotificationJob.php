<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\StatementNotificationType;
use app\Enums\StatementStatus;
use App\Exceptions\NotFoundException;
use App\Jobs\Traits\LogExecutionTrait;
use App\Models\Statement;
use App\Models\User;
use App\Notifications\NotificationFactoryRegistry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class BookingConflictNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LogExecutionTrait;

    public function __construct(
        public int $statementId,
        public int $userId,
        public StatementNotificationType $type,
    ) {}

    /**
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(): void
    {
        $statement = Statement::query()->find($this->statementId);
        $user = User::query()->find($this->userId);

        /** @var User $user */
        if (!$user || !$statement) {
            throw new NotFoundException('User or statement not found');
        }

        $statement->update([
            'status' => StatementStatus::REJECTED->value,
        ]);

        $factory = app(NotificationFactoryRegistry::class)->get($this->type);

        $user->notify($factory->createNotification($statement));
    }
}
