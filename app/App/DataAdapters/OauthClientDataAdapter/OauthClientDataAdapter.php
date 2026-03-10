<?php

namespace App\DataAdapters\OauthClientDataAdapter;

use App\Data\RefreshTokenResponseDTO\RefreshTokenResponseDTO;
use App\Data\TokensDTO\TokensDTO;
use Illuminate\Http\Client\Response;

class OauthClientDataAdapter
{
    public function createTokensOauthResponseDTO(Response $response): TokensDTO
    {
        return TokensDTO::validateAndCreate([
            'tokenData' => $response->json(),
        ]);
    }

    public function createRefreshTokenOauthResponseDTO(Response $response): RefreshTokenResponseDTO
    {
        return RefreshTokenResponseDTO::validateAndCreate([
            'tokenData' => $response->json(),
        ]);
    }
}
