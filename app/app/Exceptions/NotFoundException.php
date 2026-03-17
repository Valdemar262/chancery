<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

class NotFoundException extends Exception
{
    public $message = 'Not found';
    public $code = 404;
    public Throwable $previous;
}
