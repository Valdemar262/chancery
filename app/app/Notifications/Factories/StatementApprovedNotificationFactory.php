<?php

declare(strict_types=1);

namespace App\Notifications\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use App\Notifications\StatementApprovedNotification;

class StatementApprovedNotificationFactory extends NotificationFactory
{
    public function createNotification(Model $statement, ?User $user = null, ?User $admin = null): Notification
    {
        return new StatementApprovedNotification($statement);
    }
}
