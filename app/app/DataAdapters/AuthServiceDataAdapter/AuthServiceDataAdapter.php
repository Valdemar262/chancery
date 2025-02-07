<?php

namespace App\DataAdapters\AuthServiceDataAdapter;

use App\Data\AuthResponseDTO\AuthResponseDTO;
use App\Data\LoginDTO\LoginDTO;
use App\Data\RegisterDTO\RegisterDTO;
use App\Data\TokensDTO\TokensDTO;
use Illuminate\Contracts\Auth\Authenticatable;
use Symfony\Component\HttpFoundation\Request;
use App\Data\RefreshTokenDTO\RefreshTokenDTO;

class AuthServiceDataAdapter
{
    public function createRegisterDTOByRequest(Request $request): RegisterDTO
    {
        return $this->createRegisterDTO(
            name: $request->get('name'),
            email: $request->get('email'),
            password: $request->get('password')
        );
    }

    public function createRegisterDTO(
        string $name,
        string $email,
        string $password,
    ): RegisterDTO {
        return RegisterDTO::validateAndCreate([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function createLoginDTOByRequest(Request $request): LoginDTO
    {
        return $this->createLoginDTO(
            email: $request->get('email'),
            password: $request->get('password'),
        );
    }

    public function createLoginDTO(
        string $email,
        string $password,
    ): LoginDTO {
        return LoginDTO::validateAndCreate([
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function createRefreshTokenDTOByRequest(Request $request): RefreshTokenDTO
    {
        return $this->createRefreshTokenDTO(
            refreshToken: $request->get('refreshToken'),
        );
    }

    public function createRefreshTokenDTO(string $refreshToken): RefreshTokenDTO
    {
        return RefreshTokenDTO::validateAndCreate([
            'refreshToken' => $refreshToken,
        ]);
    }

    public static function createAuthorizationResponse(Authenticatable $user, TokensDTO $tokensDTO): AuthResponseDTO
    {
        return AuthResponseDTO::validateAndCreate([
            'user' => $user,
            'tokenData' => $tokensDTO->tokenData,
        ]);
    }
}
