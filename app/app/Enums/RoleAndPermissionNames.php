<?php

namespace App\Enums;

use Illuminate\Support\Collection;

class RoleAndPermissionNames
{
    const ROLE_CLIENT = 'client';

    const ROLE_ADMIN = 'admin';

    /**
     * @return Collection<string, string>
     */
    public static function getRoles(): Collection
    {
        return collect([
            'client' => self::ROLE_CLIENT,
            'admin' => self::ROLE_ADMIN,
        ]);
    }
}
