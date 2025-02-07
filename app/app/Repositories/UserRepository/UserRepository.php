<?php

namespace App\Repositories\UserRepository;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function findById(int $id): User
    {
        return User::find($id);
    }

    public function getAll(): Collection
    {
        return User::all();
    }

    public function destroy(int $id): int
    {
        return User::destroy($id);
    }

    public function findByEmail(string $email): User
    {
        return User::where('email', $email)->first();
    }
}
