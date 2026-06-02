<?php

declare(strict_types=1);

namespace App\DataAdapters\StatementServiceDataAdapter;

use App\Data\StatementDTO\StatementsTrendDTO;
use App\Data\StatementsSummaryReportDTO\StatementsSummaryReportDTO;
use Illuminate\Support\Collection;

class StatementDataAdapter
{
    public function createStatementsTrendDTOsFromCollection(Collection $collection): Collection
    {
        return $collection->map(fn (object $row) => $this->createStatementsTrendDTO(
            period: $row->period,
            count: (int) $row->count,
        ));
    }

    public function createStatementsTrendDTO(string $period, int $count): StatementsTrendDTO
    {
        return StatementsTrendDTO::validateAndCreate([
            'period' => $period,
            'count'  => $count,
        ]);
    }

    public function createdStatementsSummaryReportDTOByArray(
        Collection $statementSummaryReportArray
    ): StatementsSummaryReportDTO {
        return $this->createdStatementsSummaryReportDTO(
            draft: $statementSummaryReportArray['draft'],
            submitted: $statementSummaryReportArray['submitted'],
            approved: $statementSummaryReportArray['approved'],
            rejected: $statementSummaryReportArray['rejected'],
            totals: $statementSummaryReportArray['totals'],
            period: $statementSummaryReportArray['period'],
        );
    }

    public function createdStatementsSummaryReportDTO(
        int    $draft,
        int    $submitted,
        int    $approved,
        int    $rejected,
        int    $totals,
        string $period,
    ): StatementsSummaryReportDTO {
        return StatementsSummaryReportDTO::validateAndCreate([
            'draft'     => $draft,
            'submitted' => $submitted,
            'approved'  => $approved,
            'rejected'  => $rejected,
            'totals'    => $totals,
            'period'    => $period,
        ]);
    }
}
