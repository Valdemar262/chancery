<?php

declare(strict_types=1);

namespace App\Jobs;

use app\Enums\StatementStatus;
use App\Exceptions\NotFoundException;
use App\Jobs\Traits\LogExecutionTrait;
use App\Models\Statement;
use App\Models\User;
use App\Notifications\BookingConflictNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BookingConflictNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LogExecutionTrait;

    public function __construct(
        public int $statementId,
        public int $userId,
    ) {}

    /**
     * @throws NotFoundException
     */
    public function handle(): void
    {
        $statement = Statement::query()->find($this->statementId)->first();
        $user = User::query()->find($this->userId)->first();

        if (!$user || !$statement) {
            throw new NotFoundException('User or statement not found');
        }

        $statement->update([
            'status' => StatementStatus::REJECTED->value,
        ]);

        $user->notify(new BookingConflictNotification($statement));
    }
}
