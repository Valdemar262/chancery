<?php

namespace App\Data\LoginDTO;

use App\Data\BaseDTO\BaseDTO;

class LoginDTO extends BaseDTO
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
        parent::__construct();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public static function rules(): array
    {
        return [
            'email' => ['email', 'required'],
            'password' => ['required'],
        ];
    }
}
