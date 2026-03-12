<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Statement;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatementApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Statement|Model $statement,
    ) {}

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Statement Approved')
            ->greeting('Hello!')
            ->line('Your statement has been approved!')
            ->line('Thank you for using our application!')
            ->line('ID заявки: '.$this->statement->id)
            ->line('Название: '.$this->statement->title)
            ->line('Номер: '.$this->statement->number)
            ->line('Дата: '.$this->statement->date);
    }
}
