<?php

declare (strict_types=1);

namespace App\Reports\Csv;

use App\Data\StatementsSummaryReportDTO\StatementsSummaryReportDTO;
use Traversable;

class StatementsSummaryCsvReport extends CsvReport
{
    public function __construct(
        private readonly StatementsSummaryReportDTO $summary,
    ) {}

    protected static function prefix(): string
    {
        return 'statement_summary_report';
    }

    protected static function headers(): array
    {
        return ['Status', 'Count'];
    }

    protected function rows(): Traversable
    {
        yield ['Draft', $this->summary->draft];
        yield ['Submitted', $this->summary->submitted];
        yield ['Approved', $this->summary->approved];
        yield ['Rejected', $this->summary->rejected];
        yield ['Totals', $this->summary->totals];
        yield ['Period', $this->summary->period];
    }
}
