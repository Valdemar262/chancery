<?php

namespace App\DataAdapters\UserServiceDataAdapter;

use App\Data\AllUsersDTO\AllUsersDTO;
use App\Data\RegisterDTO\RegisterDTO;
use App\Data\UserDTO\UserDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Request;
use App\Models\User;
use App\Data\UserRoleAndPermissionDTO\UserRoleAndPermissionDTO;

class UserServiceDataAdapter
{
    public function createUser(
        string $name,
        string $email,
        string $password,
        ?string $phone,
        ?string $address,
        ?string $birthday,
    ): UserDTO {
        return UserDTO::validateAndCreate([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'phone' => $phone,
            'address' => $address,
            'birthday' => $birthday,
        ]);
    }

    public function createUserDTOByRegisterDTO(RegisterDTO $registerDTO): UserDTO
    {
        return $this->createUser(
            name: $registerDTO->name,
            email: $registerDTO->email,
            password: $registerDTO->password,
            phone: null,
            address: null,
            birthday: null,
        );
    }

    public function createUpdateUserDTOByRequest(Request $request): UserDTO
    {
        return $this->createUpdateUserDTO(
            name: $request->get('name'),
            email: $request->get('email'),
            phone: $request->get('phone'),
            address: $request->get('address'),
            birthday: $request->get('birthday'),
        );
    }

    public function createUpdateUserDTO(
        string  $name,
        string  $email,
        ?string $phone,
        ?string $address,
        ?string $birthday,
    ): UserDTO {
        return UserDTO::validateAndCreate([
            'name' => $name,
            'email' => $email,
            'avatar' => $phone,
            'phone' => $address,
            'nickname' => $birthday,
        ]);
    }

    public function createResponseClientUserDTO(User $user): UserDTO
    {
        return UserDTO::validateAndCreate([
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'phone' => $user->phone,
            'nickname' => $user->nickname,
        ]);
    }

    public function createAllUsersDTO(Collection $users): AllUsersDTO
    {
        return AllUsersDTO::validateAndCreate([
            'allUsers' => $users,
        ]);
    }

    public function createUserRoleAndPermissionDTO(User $user): UserRoleAndPermissionDTO
    {
        return UserRoleAndPermissionDTO::validateAndCreate([
            'user' => $user,
        ]);
    }
}
