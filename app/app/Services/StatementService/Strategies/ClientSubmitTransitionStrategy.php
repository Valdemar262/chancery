<?php

declare(strict_types=1);

namespace App\Services\StatementService\Strategies;

use App\Enums\StatementStatus;
use App\Events\StatementSubmitted;
use App\Models\Statement;
use App\Models\User;
use App\Services\StatementService\Strategies\Contracts\StatusTransitionStrategyInterface;

class ClientSubmitTransitionStrategy implements StatusTransitionStrategyInterface
{
    public function canTransition(Statement $statement, ?User $actor = null): bool
    {
        return $statement->status === StatementStatus::DRAFT;
    }

    public function execute(Statement $statement, ?User $actor = null): Statement
    {
        $statement->update([
            'status' => StatementStatus::SUBMITTED->value,
        ]);

        event(new StatementSubmitted($statement->fresh()));

        return $statement->fresh();
    }
}
