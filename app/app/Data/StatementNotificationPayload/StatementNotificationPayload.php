<?php

declare(strict_types=1);

namespace App\Data\StatementNotificationPayload;

use App\Models\Statement;
use App\Models\User;

readonly class StatementNotificationPayload
{
    public function __construct(
        public Statement $statement,
        public ?User     $user = null,
        public ?User     $admin = null,
    ) {}
}
