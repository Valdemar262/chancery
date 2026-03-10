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

    /**
     * @return array<string, array<int, string>>
     */
    public static function rules(): array
    {
        return [
            'refreshToken' => ['required', 'string'],
        ];
    }
}
