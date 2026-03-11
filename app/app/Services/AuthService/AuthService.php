<?php

namespace App\Services\AuthService;

use App\Clients\OauthClient\OauthClient;
use App\Data\AuthResponseDTO\AuthResponseDTO;
use App\Data\LoginDTO\LoginDTO;
use App\Data\RefreshTokenDTO\RefreshTokenDTO;
use App\Data\RefreshTokenResponseDTO\RefreshTokenResponseDTO;
use App\Data\RegisterDTO\RegisterDTO;
use App\DataAdapters\AuthServiceDataAdapter\AuthServiceDataAdapter;
use App\DataAdapters\UserServiceDataAdapter\UserServiceDataAdapter;
use App\Repositories\UserRepository\UserRepository;
use App\Services\UserService\UserService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class AuthService
{
    public function __construct(
        public OauthClient            $oauthClient,
        public AuthServiceDataAdapter $authServiceDataAdapter,
        public UserService            $userService,
        public UserServiceDataAdapter $userServiceDataAdapter,
        public UserRepository         $userRepository,
    ) {}

    public function register(RegisterDTO $registerDTO): AuthResponseDTO
    {
        $user = $this->userService->createUser(
            $this->userServiceDataAdapter->createUserDTOByRegisterDTO($registerDTO)
        );

        return $this->authServiceDataAdapter->createAuthorizationResponse(
            user: $user,
            tokensDTO: $this->oauthClient->register($registerDTO),
        );
    }

    public function login(LoginDTO $loginDTO): AuthResponseDTO
    {
        return $this->authServiceDataAdapter->createAuthorizationResponse(
            user: $this->userRepository->findByEmail($loginDTO->email),
            tokensDTO: $this->oauthClient->login($loginDTO),
        );
    }

    public function refreshToken(RefreshTokenDTO $refreshTokenDTO): RefreshTokenResponseDTO
    {
        return $this->oauthClient->refreshToken($refreshTokenDTO);
    }

    public function me(): Authenticatable
    {
        return auth()->user();
    }

    public function logoutUser(): void
    {
        Auth::user()->tokens()->delete();
    }

    public function checkRegistrationUser(Request $request): bool
    {
        return Auth::guard('web')->attempt(
            [
                'email' => $request->get('email'),
                'password' => $request->get('password')
            ]);
    }
}
