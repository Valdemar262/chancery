<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    protected UserRepository $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new UserRepository();
    }

    public function test_findById()
    {
        $user = User::factory()->create();

        $foundUser = $this->repository->findById($user->id);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    public function test_getAll()
    {
        User::factory()->count(5)->create();

        $users = $this->repository->getAll();

        $this->assertInstanceOf(Collection::class, $users);
        $this->assertCount(25, $users);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();

        $deletedRows = $this->repository->destroy($user->id);
        $foundUser = User::find($user->id);

        $this->assertEquals(1, $deletedRows);
        $this->assertNull($foundUser);
    }

    public function test_findByEmail()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $foundUser = $this->repository->findByEmail('test@example.com');

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->email, $foundUser->email);
    }
}

