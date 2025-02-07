<?php

namespace App\Data\AllUsersDTO;

use App\Data\BaseDTO\BaseDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AllUsersDTO extends BaseDTO
{
    /**
     * @param Collection<int, User> $allUsers
     */
    public function __construct(
        public Collection $allUsers,
    ) {
        parent::__construct();
    }
}
