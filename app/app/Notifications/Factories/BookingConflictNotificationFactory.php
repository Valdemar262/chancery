<?php

declare(strict_types=1);

namespace App\Notifications\Factories;

use App\Models\User;
use App\Notifications\BookingConflictNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class BookingConflictNotificationFactory extends NotificationFactory
{
    public function createNotification(Model $statement, ?User $user = null, ?User $admin = null): Notification
    {
        return new BookingConflictNotification($statement);
    }
}
