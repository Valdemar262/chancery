<?php

namespace App\Data\AllStatementsDTO;

use App\Data\BaseDTO\BaseDTO;
use Illuminate\Database\Eloquent\Collection;

class AllStatementsDTO extends BaseDTO
{
    public function __construct(
        public Collection $allStatements,
    ) {
        parent::__construct();
    }
}
