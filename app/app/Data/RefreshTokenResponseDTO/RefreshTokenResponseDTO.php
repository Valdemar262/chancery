<?php

namespace App\Data\RefreshTokenResponseDTO;

use App\Data\BaseDTO\BaseDTO;

class RefreshTokenResponseDTO extends BaseDTO
{
    /**
     * @param array<string, string|int> $tokenData
     */
    public function __construct(
        public array $tokenData,
    ) {
        parent::__construct();
    }
}
