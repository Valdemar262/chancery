<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

class ServerException extends Exception
{
    public $message = 'Server error';
    public $code = 500;
    public Throwable $previous;
}
