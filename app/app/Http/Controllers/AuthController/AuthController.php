<?php

namespace App\Http\Controllers\AuthController;

use App\DataAdapters\AuthServiceDataAdapter\AuthServiceDataAdapter;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Services\AuthService\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        public AuthService            $authService,
        public AuthServiceDataAdapter $authServiceDataAdapter,
    ) {}

    public function register(Request $request): JsonResponse
    {
        try {
            return getSuccessResponse(
                $this->authService->register(
                    $this->authServiceDataAdapter->createRegisterDTOByRequest($request)
                ));
        } catch (\Exception $e) {

            return getSuccessResponse($e->getMessage());
        }
    }

    public function login(Request $request): JsonResponse
    {
        if ($this->authService->checkRegistrationUser($request)) {
            return getSuccessResponse(
                $this->authService->login(
                    $this->authServiceDataAdapter->createLoginDTOByRequest($request)
                ));
        } else {
            return getSuccessResponse(ResponseMessages::UNAUTHORIZED_MESSAGE);
        }
    }

    public function me(): JsonResponse
    {
        return getSuccessResponse($this->authService->me());
    }

    public function refresh(Request $request): JsonResponse
    {
        return getSuccessResponse($this->authService->refreshToken(
            $this->authServiceDataAdapter->createRefreshTokenDTOByRequest($request)
        ));
    }

    public function logout(): JsonResponse
    {
        $this->authService->logoutUser();

        return getSuccessResponse(ResponseMessages::LOG_OUT_MESSAGE);
    }
}
