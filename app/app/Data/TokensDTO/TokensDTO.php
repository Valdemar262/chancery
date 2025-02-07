<?php

namespace App\Data\TokensDTO;

use App\Data\BaseDTO\BaseDTO;

class TokensDTO extends BaseDTO
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
