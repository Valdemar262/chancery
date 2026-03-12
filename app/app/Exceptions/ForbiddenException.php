<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class ForbiddenException extends AuthorizationException
{
    public function __construct(string $message = 'Forbidden')
    {
        parent::__construct($message);
    }
}
