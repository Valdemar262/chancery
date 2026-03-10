<?php

namespace Tests\Feature;

use App\Services\FakerClient\FakerClient;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->fakerClient = $this->app->make(FakerClient::class);
    }

    public function test_showUser(): void
    {
        $userAndUserData = $this->createRegistrationUser();

        $currentUser = $this->withHeaders(['Authorization' => 'Bearer ' . $userAndUserData['token']])
            ->get(
                '/api/showUser/' . $userAndUserData['id'],
                (array)$userAndUserData['userData'],
            );

        $this->assertEquals(
            $userAndUserData['userData']->name,
            $currentUser->baseResponse->getOriginalContent()->name,
        );
    }

    public function createRegistrationUser(): array
    {
        $userData = $this->createUserData();

        $user = $this->post('/api/register', (array)$userData);

        $id = $user->baseResponse->original->user['id'];

        $token = $user->baseResponse->original->tokenData['access_token'];

        return [
            'userData' => $userData,
            'token' => $token,
            'user' => $user,
            'id' => $id,
        ];
    }

    public function createUserData(): \stdClass
    {
        $user = new \stdClass();
        $user->name = $this->fakerClient->faker->name();
        $user->email = $this->fakerClient->faker->email();
        $user->password = $this->fakerClient->faker->password(8);

        return $user;
    }

    public function test_updateUser(): void
    {
        $userAndUserData = $this->createRegistrationUser();

        $updatedUser = $this->createUpdateUserData($userAndUserData['id']);

        $updateResponse = $this->withHeaders(['Authorization' => 'Bearer ' . $userAndUserData['token']])
            ->put(
                '/api/updateUser',
                (array)$updatedUser,
            );

        $this->assertEquals(
            $updatedUser->name,
            $updateResponse->baseResponse->getOriginalContent()->name
        );
    }

    public function createUpdateUserData(int $id): \stdClass
    {
        $updateUser = new \stdClass();
        $updateUser->name = $this->fakerClient->faker->name();
        $updateUser->email = $this->fakerClient->faker->email();
        $updateUser->avatar = $this->fakerClient->faker->imageUrl();
        $updateUser->phone = $this->fakerClient->faker->phoneNumber();
        $updateUser->nickname = $this->fakerClient->faker->name();
        $updateUser->id = $id;

        return $updateUser;
    }

    public function test_deleteUser(): void
    {
        $userAndUserData = $this->createRegistrationUser();

        $this->withHeaders(['Authorization' => 'Bearer ' . $userAndUserData['token']])
            ->delete('/api/deleteUser/' . $userAndUserData['id']);

        $this->assertDatabaseMissing(
            'users',
            ['id' => $userAndUserData['id']],
        );
    }
}
