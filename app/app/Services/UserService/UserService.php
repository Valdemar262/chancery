<?php

namespace App\Services\UserService;

use App\Data\AllUsersDTO\AllUsersDTO;
use App\Data\UserDTO\UserDTO;
use App\DataAdapters\UserServiceDataAdapter\UserServiceDataAdapter;
use App\Enums\AuthResponseMessages;
use App\Models\User;
use App\Repositories\UserRepository\UserRepository;
use App\Services\RoleAndPermissionService\RoleAndPermissionService;

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

    public function showUser(int $id): UserDTO
    {
        $user = $this->userRepository->findById($id);

        return $this->userServiceDataAdapter->createResponseClientUserDTO($user);
    }

    public function updateUser(UserDTO $updateUserDTO, User $user): UserDTO
    {
        $user->update([
            'name' => $updateUserDTO->name,
            'email' => $updateUserDTO->email,
            'phone' => $updateUserDTO->phone,
            'address' => $updateUserDTO->address,
            'birthday' => $updateUserDTO->birthday,
            'update_at' => now(),
        ]);

        return $this->userServiceDataAdapter->createResponseClientUserDTO($user);
    }

    public function deleteUser(int $id): string
    {
        $this->userRepository->destroy($id);

        return AuthResponseMessages::DELETE_USER_MESSAGE;
    }

    public function truncate(): void
    {
        User::truncate();
    }

    public function createUser(UserDTO $userDTO): User
    {
        $user = User::create($userDTO->toArray());

        $this->assignClientRole($user);

        return $user;
    }

    public function assignClientRole(User $user): string
    {
        $this->roleAndPermissionService->assignClientRole($user);

        return AuthResponseMessages::CLIENT_ROLE_APPOINTED;
    }

    public function assignAdminRole(User $user): string
    {
        $this->roleAndPermissionService->assignAdminRole($user);

        return AuthResponseMessages::ADMIN_ROLE_APPOINTED;
    }

    public function unassignClientRole(User $user): string
    {
        $this->roleAndPermissionService->removeClientRole($user);

        return AuthResponseMessages::CLIENT_ROLE_REMOVED;
    }

    public function unassignAdminRole(User $user): string
    {
        $this->roleAndPermissionService->removeAdminRole($user);

        return AuthResponseMessages::ADMIN_ROLE_REMOVED;
    }
}
