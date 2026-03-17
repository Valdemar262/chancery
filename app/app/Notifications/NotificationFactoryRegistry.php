<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\StatementNotificationType;
use App\Notifications\Factories\BookingConflictNotificationFactory;
use App\Notifications\Factories\BookingCreatedNotificationFactory;
use App\Notifications\Factories\NotificationFactory;
use App\Notifications\Factories\StatementApprovedNotificationFactory;
use App\Notifications\Factories\StatementRejectedNotificationFactory;
use App\Notifications\Factories\StatementSubmittedNotificationFactory;
use InvalidArgumentException;

class NotificationFactoryRegistry
{
    private array $map = [
        StatementNotificationType::SUBMITTED->value        => StatementSubmittedNotificationFactory::class,
        StatementNotificationType::APPROVED->value         => StatementApprovedNotificationFactory::class,
        StatementNotificationType::REJECTED->value         => StatementRejectedNotificationFactory::class,
        StatementNotificationType::BOOKING_CONFLICT->value => BookingConflictNotificationFactory::class,
        StatementNotificationType::BOOKING_CREATED->value  => BookingCreatedNotificationFactory::class,
    ];

    public function get(StatementNotificationType $type): NotificationFactory
    {
        $factoryClass = $this->map[$type->value] ?? null;
        if (!$factoryClass) {
            throw new InvalidArgumentException("Unknown notification type: $type->value");
        }
        return app($factoryClass);
    }
}
