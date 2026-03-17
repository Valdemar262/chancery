<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Statement;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatementSubmitted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Statement $statement,
    ) {}

    public function broadcastOn(): array
    {
        $channels = [new PrivateChannel('user.' . $this->statement->user_id)];
        $channels[] = new PrivateChannel('admin');
        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'statement.submitted';
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
