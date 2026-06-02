<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

class CollectionEmptyException extends Exception
{
    public function __construct(
        string     $message = 'Collection entities is empty',
        int        $code = 400,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
