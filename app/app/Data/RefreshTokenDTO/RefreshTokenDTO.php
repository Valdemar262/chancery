<?php

namespace App\Data\RefreshTokenDTO;

use \App\Data\BaseDTO\BaseDTO;

class RefreshTokenDTO extends BaseDTO
{
    public function __construct(
        public string $refreshToken,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'refreshToken' => ['required', 'string'],
        ];
    }
}
