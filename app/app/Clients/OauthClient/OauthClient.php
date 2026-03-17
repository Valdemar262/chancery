<?php

namespace App\Clients\OauthClient;

use App\Data\LoginDTO\LoginDTO;
use App\Data\RefreshTokenDTO\RefreshTokenDTO;
use App\Data\RefreshTokenResponseDTO\RefreshTokenResponseDTO;
use App\Data\RegisterDTO\RegisterDTO;
use App\Data\TokensDTO\TokensDTO;
use App\DataAdapters\OauthClientDataAdapter\OauthClientDataAdapter;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class OauthClient
{
    public function __construct(
        readonly private OauthClientDataAdapter $oauthClientDataAdapter,
    ) {}

    public function register(RegisterDTO $registerDTO): TokensDTO
    {
        return $this->oauthClientDataAdapter->createTokensOauthResponseDTO(
            $this->makePostRequest($registerDTO->email, $registerDTO->password)
        );
    }

    public function login(LoginDTO $loginDTO): TokensDTO
    {
        return $this->oauthClientDataAdapter->createTokensOauthResponseDTO(
            $this->makePostRequest($loginDTO->email, $loginDTO->password)
        );
    }

    public function refreshToken(RefreshTokenDTO $refreshTokenDTO): RefreshTokenResponseDTO
    {
        return $this->oauthClientDataAdapter->createRefreshTokenOauthResponseDTO(
            Http::asForm()->post(config('app.OAUTH_URL') . '/oauth/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshTokenDTO->refreshToken,
                'client_id' => config('app.PASSPORT_PASSWORD_CLIENT_ID'),
                'client_secret' => config('app.PASSPORT_PASSWORD_SECRET'),
                'scope' => '',
            ])
        );
    }

    public function makePostRequest(string $email, string $password): Response
    {
        return Http::post(config('app.OAUTH_URL') . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => config('app.PASSPORT_PASSWORD_CLIENT_ID'),
            'client_secret' => config('app.PASSPORT_PASSWORD_SECRET'),
            'username' => $email,
            'password' => $password,
            'scope' => '',
        ]);
    }
}
