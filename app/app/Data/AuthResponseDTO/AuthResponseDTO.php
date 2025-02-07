<?php

namespace App\Data\AuthResponseDTO;

use App\Data\BaseDTO\BaseDTO;
use App\Models\User;

class AuthResponseDTO extends BaseDTO
{
    /**
     * @param array<string, string|int> $tokenData
     */
    public function __construct(
        public User   $user,
        public array $tokenData,
    ) {
        parent::__construct();
    }
}
