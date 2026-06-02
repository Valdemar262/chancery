<?php

declare(strict_types=1);

namespace App\Notifications\Factories;

use App\Models\Statement;
use App\Models\User;
use Illuminate\Notifications\Notification;

abstract class NotificationFactory
{
    abstract public function createNotification(Statement $statement, ?User $user = null, ?User $admin = null): Notification;
}
