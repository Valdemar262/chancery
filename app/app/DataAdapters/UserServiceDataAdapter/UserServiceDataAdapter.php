<?php

namespace App\DataAdapters\UserServiceDataAdapter;

use App\Data\AllUsersDTO\AllUsersDTO;
use App\Data\RegisterDTO\RegisterDTO;
use App\Data\UserDTO\UserDTO;
use App\Data\UserDTO\UserOperationDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Request;
use App\Models\User;

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

    public function createUserOperationDTO(User $user): UserOperationDTO
    {
        return UserOperationDTO::validateAndCreate([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'birthday' => $user->birthday,
        ]);
    }

    public function createUserOperationDTOByRequest(Request $request): UserOperationDTO
    {
        return $this->createUserOperationDataDTO(
            id: $request->get('id'),
            name: $request->get('name'),
            email: $request->get('email'),
            phone: $request->get('phone'),
            address: $request->get('address'),
            birthday: $request->get('birthday'),
        );
    }

    public function createUserOperationDataDTO(
        int     $id,
        string  $name,
        string  $email,
        ?string $phone,
        ?string $address,
        ?string $birthday,
    ): UserOperationDTO {
        return UserOperationDTO::validateAndCreate([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'birthday' => $birthday
        ]);
    }

    public function createAllUsersDTO(Collection $users): AllUsersDTO
    {
        return AllUsersDTO::validateAndCreate([
            'allUsers' => $users,
        ]);
    }
}
