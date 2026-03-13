<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Statement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatementRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Statement|Model $statement,
        public User            $user,
        public User            $admin,
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Statement Rejected')
            ->greeting('Hello! ' . $this->user->name)
            ->line('The statement has been canceled.')
            ->line('ID statement: ' . $this->statement->id)
            ->line('Date: ' . $this->statement->date)
            ->line('Regards, ' . $this->admin->name);
    }
}
