<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Statement;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatementRejected implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Statement  $statement,
        public User|Model $user,
        public User       $admin,
    ) {}

    public function broadcastOn(): array
    {
        $channels = [new PrivateChannel('user.' . $this->statement->user_id)];
        $channels[] = new PrivateChannel('admin');
        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'statement.rejected';
    }

    public function broadcastWith(): array
    {
        return [
            'statement_id' => $this->statement->id,
            'title'        => $this->statement->title,
            'status'       => $this->statement->status->value,
        ];
    }
}
