<?php

declare(strict_types=1);

namespace App\Data\StatementsSummaryReportDTO;

use App\Data\BaseDTO\BaseDTO;

class StatementsSummaryReportDTO extends BaseDTO
{
    public function __construct(
        public int    $draft,
        public int    $submitted,
        public int    $approved,
        public int    $rejected,
        public int    $totals,
        public string $period,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'draft'     => 'required|int',
            'submitted' => 'required|int',
            'approved'  => 'required|int',
            'rejected'  => 'required|int',
            'totals'    => 'required|int',
            'period'    => 'required|string',
        ];
    }
}
