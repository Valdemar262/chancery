<?php

declare(strict_types=1);

namespace App\Enums;

use Carbon\Carbon;

enum ReportPeriod: string
{
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';
    case YEAR = 'year';
    case ALL = 'all';

    public function getStartDate(): ?Carbon
    {
        return match ($this) {
            self::DAY => now()->startOfDay(),
            self::WEEK => now()->startOfWeek(),
            self::MONTH => now()->startOfMonth(),
            self::YEAR => now()->startOfYear(),
            self::ALL => null,
        };
    }
}
