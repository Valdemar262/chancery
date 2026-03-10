<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    const PERSONAL_INFORMATION = [
        'email' => 'email@email.com',
        'password' => 'password',
    ];

    const NAME = 'name';

    public function test_register(): void
    {
        $data = [
            'name' => self::NAME,
            'email' => self::PERSONAL_INFORMATION['email'],
            'password' => self::PERSONAL_INFORMATION['password'],
        ];

        $this->post('/api/register', $data);

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);
    }

    public function test_login(): void
    {
        $response = $this->post('/api/login', self::PERSONAL_INFORMATION);

        $this->assertNotEmpty($response->baseResponse->original->tokenData['access_token']);
    }

    public function test_refresh(): void
    {
        $loginResponse = $this->post('/api/login', self::PERSONAL_INFORMATION);

        $data = [
            'refreshToken' => $loginResponse->baseResponse->original->tokenData['refresh_token'],
        ];

        $response = $this->post('/api/refresh', $data);

        $this->assertNotEmpty($response->baseResponse->original->tokenData['refresh_token']);
    }
}
