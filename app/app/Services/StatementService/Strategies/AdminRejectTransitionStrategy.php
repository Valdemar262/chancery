<?php

declare(strict_types=1);

namespace App\Services\StatementService\Strategies;

use app\Enums\StatementStatus;
use App\Events\StatementRejected;
use App\Exceptions\ForbiddenException;
use App\Models\Statement;
use App\Models\User;
use App\Services\RoleAndPermissionService\RoleAndPermissionChecker;
use App\Services\StatementService\Strategies\Contracts\StatusTransitionStrategyInterface;

readonly class AdminRejectTransitionStrategy implements StatusTransitionStrategyInterface
{
    public function __construct(
        private RoleAndPermissionChecker $roleChecker,
    ) {}

    public function canTransition(Statement $statement, ?User $actor = null): bool
    {
        if ($actor === null) {
            return false;
        }

        if (!$this->roleChecker->hasAdminRole($actor)) {
            throw new ForbiddenException('User does not have admin role');
        }

        return $statement->status === StatementStatus::SUBMITTED;
    }

    public function execute(Statement $statement, ?User $actor = null): Statement
    {
        $statement->update([
            'status' => StatementStatus::REJECTED->value,
        ]);

        $user = User::query()->find($statement->user_id);

        event(new StatementRejected($statement->fresh(), $user, $actor));

        return $statement->fresh();
    }
}
