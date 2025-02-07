<?php

namespace App\Data\TokensDTO;

use App\Data\BaseDTO\BaseDTO;

class TokensDTO extends BaseDTO
{
    public function __construct(
        public array $tokenData,
    ) {
        parent::__construct();
    }
}
