<?php

declare(strict_types=1);

namespace app\Notifications;

use App\Models\Statement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;

class StatementSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Statement|Model $statement,
    ) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Новая заявка на рассмотрение')
            ->greeting('Здравствуйте!')
            ->line('Поступила новая заявка на рассмотрение.')
            ->line('ID заявки: '.$this->statement->id)
            ->line('Название: '.$this->statement->title)
            ->line('Номер: '.$this->statement->number)
            ->line('Дата: '.$this->statement->date)
            ->line('Пожалуйста, проверьте заявку и измените её статус.');
    }
}
