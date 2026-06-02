<?php

declare(strict_types=1);

namespace App\Services\RoleAndPermissionService;

use App\Exceptions\ForbiddenException;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class RoleAndPermissionChecker
{
    public function __construct() {}

    /**
     * @throws ForbiddenException
     */
    public function hasAdminRole(User|Authenticatable $user): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }
}
