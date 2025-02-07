<?php

namespace App\Services\RoleAndPermissionService;

use App\Enums\RoleAndPermissionNames;
use App\Models\User;

class RoleAndPermissionService
{
    public function assignClientRole(User $user): void
    {
        $user->assignRole(RoleAndPermissionNames::ROLE_CLIENT);
    }

    public function removeClientRole(User $user): void
    {
        $user->removeRole(RoleAndPermissionNames::ROLE_CLIENT);
    }

    public function assignAdminRole(User $user): void
    {
        $user->assignRole(RoleAndPermissionNames::ROLE_ADMIN);
    }

    public function removeAdminRole(User $user): void
    {
        $user->removeRole(RoleAndPermissionNames::ROLE_ADMIN);
    }
}
