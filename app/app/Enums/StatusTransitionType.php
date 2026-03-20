<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusTransitionType: string
{
    case SUBMIT = 'submit';
    case APPROVE = 'approve';
    case REJECT = 'reject';
}
