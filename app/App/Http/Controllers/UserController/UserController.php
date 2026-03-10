<?php

namespace App\Http\Controllers\UserController;

use App\DataAdapters\UserServiceDataAdapter\UserServiceDataAdapter;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService            $userService,
        private readonly UserServiceDataAdapter $userServiceDataAdapter,
    ) {}

    public function getAllUsers(): JsonResponse
    {
        return getSuccessResponse(
            $this->userService->allUsers()
        );
    }

    public function showUser(User $user): JsonResponse
    {
        return getSuccessResponse(
            $this->userService->showUser($user)
        );
    }

    public function updateUser(Request $request): JsonResponse
    {
        return getSuccessResponse(
            $this->userService->updateUser(
                $this->userServiceDataAdapter->createUserOperationDTOByRequest($request)
            )
        );
    }

    public function deleteUser(int $id): JsonResponse
    {
        return getSuccessResponse($this->userService->deleteUser($id));
    }

    public function assignRole(User $user, Request $request): JsonResponse
    {
        return getSuccessResponse(
            $this->userService->assignRole(
                $user,
                $request,
            )
        );
    }

    public function removeRole(User $user, Request $request): JsonResponse
    {
        return getSuccessResponse(
            $this->userService->removeRole(
                $user,
                $request,
            )
        );
    }
}
