<?php

namespace App\Enums;

class AuthResponseMessages
{
    const LOG_OUT_MESSAGE = 'User successfully signed out !';

    const UNAUTHORIZED_MESSAGE = 'Unauthorized';

    const DELETE_USER_MESSAGE = 'User deleted successfully';

    const CLIENT_ROLE_APPOINTED = 'Client role assigned successfully';

    const ADMIN_ROLE_APPOINTED = 'Admin role assigned successfully';

    const CLIENT_ROLE_REMOVED = 'Client role removed successfully';

    const ADMIN_ROLE_REMOVED = 'Admin role removed successfully';
}
