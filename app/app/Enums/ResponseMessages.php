<?php

declare(strict_types=1);

namespace App\Enums;

class ResponseMessages
{
    const string LOG_OUT_MESSAGE = 'User logged out successfully';

    const string UNAUTHORIZED_MESSAGE = 'Unauthorized';

    const string DELETE_USER_MESSAGE = 'User deleted successfully';

    const string ROLE_APPOINTED = 'Role assigned successfully';

    const string ROLE_DOES_NOT_EXIST = 'Role does not exist';

    const string ROLE_REMOVED = 'Role removed successfully';

    const string DELETE_STATEMENT_SUCCESS = 'Statement deleted successfully';

    const string DELETE_BOOKING_SUCCESS = 'Booking deleted successfully';

    const string BOOKING_NOT_FOUND = 'Booking not found';

    const string DELETE_RESOURCE_SUCCESS = 'Resource deleted successfully';
}
