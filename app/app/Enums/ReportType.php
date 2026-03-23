<?php

declare (strict_types = 1);

namespace App\Enums;

enum ReportType: string
{
    case STATEMENTS_SUMMARY = 'statements_summary';
    case BOOKINGS_BY_RESOURCE = 'bookings_by_resource';
    case USER_ACTIVITY = 'user_activity';
    case STATEMENTS_TREND = 'statements_trend';
}
