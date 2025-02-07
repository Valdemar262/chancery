<?php

namespace Tests\Unit\Services;

use App\Data\AuthResponseDTO\AuthResponseDTO;
use App\Data\LoginDTO\LoginDTO;
use App\Data\RegisterDTO\RegisterDTO;
use App\Data\RefreshTokenDTO\RefreshTokenDTO;
use App\Models\User;
use App\Services\AuthService\AuthService;
use Tests\TestCase;
use App\Services\FakerClient\FakerClient;

class AuthServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->authService = $this->app->make(AuthService::class);
        $this->fakerClient = $this->app->make(FakerClient::class);
    }

    public function test_auth_register(): void
    {
        $user = $this->createUserData();

        $this->executeRegister($user);

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    private function executeRegister(\stdClass $user): AuthResponseDTO
    {
        $registerDTO = RegisterDTO::validateAndCreate([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
        ]);

        return $this->authService->register($registerDTO);
    }

    public function test_auth_login(): void
    {
        $user = $this->createUserData();

        $registerResult = $this->executeRegister($user);

        $this->actingAs(User::where('name', $registerResult->user['name'])->first());

        $loginResult = $this->executeLogin($user);

        $this->assertEquals($registerResult->user->toArray(), $loginResult->user->toArray());
    }

    private function executeLogin(\stdClass $user): AuthResponseDTO
    {
        $loginDTO = LoginDTO::validateAndCreate([
            'email' => $user->email,
            'password' => $user->password,
        ]);

        return $this->authService->login($loginDTO);
    }

    public function test_auth_refreshToken(): void
    {
        $user = $this->createUserData();

        $registerResult = $this->executeRegister($user);

        $refreshTokenDTO = RefreshTokenDTO::validateAndCreate([
            'refreshToken' => $registerResult->tokenData['refresh_token'],
        ]);

        $refreshToken = $this->authService->refreshToken($refreshTokenDTO);

        $this->assertNotEmpty($refreshToken->tokenData['refresh_token']);
    }

    private function createUserData(): \stdClass
    {
        $user = new \stdClass();
        $user->name = $this->fakerClient->faker->name();
        $user->email = $this->fakerClient->faker->email();
        $user->password = $this->fakerClient->faker->password(8);

        return $user;
    }

    public function tearDown():void
    {
        User::truncate();
    }
}
