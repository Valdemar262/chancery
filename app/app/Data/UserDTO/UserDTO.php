<?php

namespace App\Data\UserDTO;

use App\Data\BaseDTO\BaseDTO;

class UserDTO extends BaseDTO
{
    public function __construct(
        public string  $name,
        public string  $email,
        public string  $password,
        public ?string $phone,
        public ?string $address,
        public ?string $birthday,
    ) {
        parent::__construct();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public static function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users'],
        ];
    }
}
