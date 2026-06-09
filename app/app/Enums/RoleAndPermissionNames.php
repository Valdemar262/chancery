<?php

namespace App\Enums;

use Illuminate\Support\Collection;

class RoleAndPermissionNames
{
    const string ROLE_CLIENT = 'client';

    const string ROLE_ADMIN = 'admin';

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
