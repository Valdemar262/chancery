<?php

namespace App\Data\AllStatementsDTO;

use App\Data\BaseDTO\BaseDTO;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Statement;

class AllStatementsDTO extends BaseDTO
{
    /**
     * @param Collection<int, Statement> $allStatements
     */
    public function __construct(
        public Collection $allStatements,
    ) {
        parent::__construct();
    }
}
