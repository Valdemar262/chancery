<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Statement;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConflictNotification extends Notification
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
            ->subject('Booking conflict created')
            ->greeting('Hello!')
            ->line('Your booking has not been created!')
            ->line('Thank you for your statement!')
            ->line('ID заявки: ' . $this->statement->id)
            ->line('Название: ' . $this->statement->title)
            ->line('Status: ' . $this->statement->status->label())
            ->line('Дата: ' . $this->statement->date);
    }
}
