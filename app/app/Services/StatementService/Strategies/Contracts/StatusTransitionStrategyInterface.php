<?php

declare(strict_types=1);

namespace App\Services\StatementService\Strategies\Contracts;

use App\Models\Statement;
use App\Models\User;

interface StatusTransitionStrategyInterface
{
    /**
     * @param Statement $statement
     * @param User|null $actor
     */
    public function canTransition(Statement $statement, ?User $actor = null): bool;

    /**
     * @param Statement $statement
     * @param User|null $actor
     * @return Statement
     */
    public function execute(Statement $statement, ?User $actor = null): Statement;
}
