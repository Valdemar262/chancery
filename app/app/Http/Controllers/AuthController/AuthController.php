<?php

namespace App\Http\Controllers\AuthController;

use App\DataAdapters\AuthServiceDataAdapter\AuthServiceDataAdapter;
use App\Enums\AuthResponseMessages;
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
            return response()->json(
                $this->authService->register(
                    $this->authServiceDataAdapter->createRegisterDTOByRequest($request)
                ));
        } catch (\Exception $e) {

            return response()->json($e->getMessage());
        }
    }

    public function login(Request $request): JsonResponse
    {
        if ($this->authService->checkRegistrationUser($request)) {
            return response()->json(
                $this->authService->login(
                    $this->authServiceDataAdapter->createLoginDTOByRequest($request)
                ));
        } else {
            return response()->json(AuthResponseMessages::UNAUTHORIZED_MESSAGE);
        }
    }

    public function me(): JsonResponse
    {
        return response()->json($this->authService->me());
    }

    public function refresh(Request $request): JsonResponse
    {
        return response()->json($this->authService->refreshToken(
            $this->authServiceDataAdapter->createRefreshTokenDTOByRequest($request)
        ));
    }

    public function logout(): JsonResponse
    {
        $this->authService->logoutUser();

        return response()->json(AuthResponseMessages::LOG_OUT_MESSAGE);
    }
}
