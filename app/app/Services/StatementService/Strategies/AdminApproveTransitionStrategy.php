<?php

declare(strict_types=1);

namespace App\Services\StatementService\Strategies;

use app\Enums\StatementStatus;
use App\Events\StatementApproved;
use App\Exceptions\ForbiddenException;
use App\Models\Resource;
use App\Models\Statement;
use App\Models\User;
use App\Services\RoleAndPermissionService\RoleAndPermissionChecker;
use App\Services\StatementService\Strategies\Contracts\StatusTransitionStrategyInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

readonly class AdminApproveTransitionStrategy implements StatusTransitionStrategyInterface
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
        $resource = Resource::query()->where('name', $statement->title)->first();

        if (!$resource) {
            throw new ModelNotFoundException('Resource not found with name: ' . $statement->title);
        }

        $statement->update([
            'resource_id' => $resource->id,
            'status'      => StatementStatus::APPROVED->value,
            'approved_by' => $actor->id,
        ]);

        $statement = $statement->fresh();

        $user = User::query()->find($statement->user_id);

        event(new StatementApproved($statement, $user, $actor));

        return $statement;
    }
}
