<?php

namespace Tests\Unit\Services;

use App\Data\AuthResponseDTO\AuthResponseDTO;
use App\Data\RegisterDTO\RegisterDTO;
use App\Data\UserDTO\UserOperationDTO;
use App\Models\User;
use App\Services\AuthService\AuthService;
use App\Services\FakerClient\FakerClient;
use App\Services\UserService\UserService;
use Tests\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class UserServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function setUp(): void
    {
        parent::setUp();

        $this->fakerClient = $this->app->make(FakerClient::class);
        $this->userService = $this->app->make(UserService::class);
        $this->authService = $this->app->make(AuthService::class);
    }

    public function test_showUser(): void
    {
        $registerUser = $this->executeRegister($this->createUserData());

        $currentUser = $this->userService->showUser(
            User::find($registerUser->user['id']),
        );

        $this->assertEquals($registerUser->user->toArray(), $currentUser->toArray());
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

    private function createUserData(): \stdClass
    {
        $user = new \stdClass();
        $user->name = $this->fakerClient->faker->name();
        $user->email = $this->fakerClient->faker->email();
        $user->password = $this->fakerClient->faker->password(8);

        return $user;
    }

    public function test_updateUser(): void
    {
        $this->expectsDatabaseQueryCount(8);

        $registerUser = $this->executeRegister($this->createUserData());

        $updateUserDTO = $this->createUserOperationDataDTO($registerUser->user['id']);

        $this->userService->updateUser($updateUserDTO);

        $this->assertDatabaseHas('users', [
            'email' => $updateUserDTO->email,
        ]);
    }

    public function test_deleteUser(): void
    {
        $registerUser = $this->executeRegister($this->createUserData())->user;

        $user = User::find($registerUser['id']);

        $this->userService->deleteUser($user->id);

        $this->assertModelMissing($user);
    }

    public function createUserOperationDataDTO(int $id): UserOperationDTO
    {
        return UserOperationDTO::validateAndCreate([
            'id' => $id,
            'name' => $this->fakerClient->faker->name(),
            'email' => $this->fakerClient->faker->email(),
            'avatar' => $this->fakerClient->faker->imageUrl(),
            'phone' => $this->fakerClient->faker->phoneNumber(),
            'nickname' => $this->fakerClient->faker->name(),
        ]);
    }
}
