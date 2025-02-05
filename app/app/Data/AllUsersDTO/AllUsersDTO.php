<?php

namespace App\Data\AllUsersDTO;

use App\Data\BaseDTO\BaseDTO;
use Illuminate\Database\Eloquent\Collection;

class AllUsersDTO extends BaseDTO
{
    public function __construct(
        public Collection $allUsers,
    ) {
        parent::__construct();
    }
}
