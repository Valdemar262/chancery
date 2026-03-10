<?php

namespace App\Services\UserService;

use App\Data\AllUsersDTO\AllUsersDTO;
use App\Data\UserDTO\UserDTO;
use App\Data\UserDTO\UserOperationDTO;
use App\DataAdapters\UserServiceDataAdapter\UserServiceDataAdapter;
use App\Enums\ResponseMessages;
use App\Enums\ErrorMessages;
use App\Enums\RoleAndPermissionNames;
use App\Models\User;
use App\Repositories\UserRepository\UserRepository;
use App\Services\RoleAndPermissionService\RoleAndPermissionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserService
{
    public function __construct(
        private readonly RoleAndPermissionService $roleAndPermissionService,
        private readonly UserServiceDataAdapter   $userServiceDataAdapter,
        private readonly UserRepository           $userRepository,
    ) {}

    public function allUsers(): AllUsersDTO
    {
        return $this->userServiceDataAdapter->createAllUsersDTO(
            $this->userRepository->getAll()
        );
    }

    public function showUser(User $user): UserOperationDTO
    {
        return $this->userServiceDataAdapter->createUserOperationDTO($user);
    }

    public function updateUser(UserOperationDTO $updateUserDTO): UserOperationDTO
    {
        try {
            $user = $this->userRepository->findById($updateUserDTO->id);
            $user->updateOrFail([
                'id' => $updateUserDTO->id,
                'name' => $updateUserDTO->name,
                'email' => $updateUserDTO->email,
                'phone' => $updateUserDTO->phone,
                'address' => $updateUserDTO->address,
                'birthday' => $updateUserDTO->birthday,
                'update_at' => now(),
            ]);

            return $this->userServiceDataAdapter->createUserOperationDTO($user);
        } catch (\Throwable $e) {
            throw new ModelNotFoundException($e->getMessage());
        }
    }

    public function deleteUser(int $id): string
    {
        if ($this->userRepository->destroy($id)) {
            return ResponseMessages::DELETE_USER_MESSAGE;
        }

        return ErrorMessages::USER_NOT_FOUND;
    }

    public function createUser(UserDTO $userDTO): User
    {
        $user = User::create($userDTO->toArray());

        $request = new Request();

        $request->replace([
            'role' => RoleAndPermissionNames::ROLE_CLIENT,
        ]);

        $this->assignRole($user, $request);

        return $user;
    }

    public function assignRole(User $user, Request $request): string
    {
        $role = $request->get('role');

        if (RoleAndPermissionNames::getRoles()->search($role)) {
            $this->roleAndPermissionService->assignRole($user, $role);
            return ResponseMessages::ROLE_APPOINTED;
        }
        return ResponseMessages::ROLE_DOES_NOT_EXIST;
    }

    public function removeRole(User $user, Request $request): string
    {
        $role = $request->get('role');

        if (RoleAndPermissionNames::getRoles()->search($role)) {
            $this->roleAndPermissionService->removeRole($user, $role);
            return ResponseMessages::ROLE_REMOVED;
        }
        return ResponseMessages::ROLE_DOES_NOT_EXIST;
    }
}
