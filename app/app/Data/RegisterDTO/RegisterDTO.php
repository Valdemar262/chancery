<?php

namespace App\Data\RegisterDTO;

use App\Data\BaseDTO\BaseDTO;

class RegisterDTO extends BaseDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
        ];
    }
}

