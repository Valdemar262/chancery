<?php

namespace App\Services\RoleAndPermissionService;

use App\Models\User;

class RoleAndPermissionService
{
    public function assignRole(User $user, string $role): void
    {
        $user->assignRole($role);
    }

    public function removeRole(User $user, string $role): void
    {
        $user->removeRole($role);
    }
}
