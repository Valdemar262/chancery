<?php

namespace App\Data\AllStatementsDTO;

use App\Data\BaseDTO\BaseDTO;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Statement;

/**
 * @OA\Schema(
 *     schema="AllStatementsDTO",
 *     title="All Statements DTO",
 *     description="DTO для передачи списка всех заявлений",
 *     type="object",
 *     required={"allStatements"},
 *     @OA\Property(
 *         property="allStatements",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Statement")
 *     )
 * )
 */
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
