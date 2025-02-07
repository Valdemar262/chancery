<?php

namespace App\Data\RefreshTokenResponseDTO;

use App\Data\BaseDTO\BaseDTO;

class RefreshTokenResponseDTO extends BaseDTO
{
    public function __construct(
        public array $tokenData,
    ) {
        parent::__construct();
    }
}
