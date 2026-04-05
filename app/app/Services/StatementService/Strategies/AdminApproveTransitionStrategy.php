<?php

declare(strict_types=1);

namespace App\Services\StatementService\Strategies;

use app\Enums\StatementStatus;
use App\Events\StatementApproved;
use App\Exceptions\ForbiddenException;
use App\Models\Statement;
use App\Models\User;
use App\Repositories\ResourceRepository\ResourceRepository;
use App\Repositories\UserRepository\UserRepository;
use App\Services\RoleAndPermissionService\RoleAndPermissionChecker;
use App\Services\StatementService\Strategies\Contracts\StatusTransitionStrategyInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

readonly class AdminApproveTransitionStrategy implements StatusTransitionStrategyInterface
{
    public function __construct(
        private RoleAndPermissionChecker $roleChecker,
        private UserRepository           $userRepository,
        private ResourceRepository       $resourceRepository,
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
        $resource = $this->resourceRepository->findByField('name', $statement->title);

        if (!$resource) {
            throw new ModelNotFoundException('Resource not found with name: ' . $statement->title);
        }

        $statement->approve($resource->id, $actor->id);

        $statement = $statement->fresh();

        $user = $this->userRepository->findById($statement->user_id);

        event(new StatementApproved($statement, $user, $actor));

        return $statement;
    }
}
