<?php

declare(strict_types=1);

namespace App\Enums;

enum StatementNotificationType: string
{
    case SUBMITTED = 'submitted';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case BOOKING_CONFLICT = 'booking_conflict';
    case BOOKING_CREATED = 'booking_created';
}
