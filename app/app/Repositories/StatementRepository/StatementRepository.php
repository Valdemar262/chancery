<?php

declare(strict_types=1);

namespace App\Repositories\StatementRepository;

use App\Data\StatementDTO\StatementDTO;
use App\Enums\ReportPeriod;
use App\Models\Statement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Spatie\LaravelData\Optional;

class StatementRepository
{
    public function findById(int|Optional $id): ?Statement
    {
        return Statement::find($id);
    }

    /**
     * @return Collection<int, Statement>
     */
    public function getAll(): Collection
    {
        return Statement::all();
    }

    public function destroy(int $id): int
    {
        return Statement::destroy($id);
    }

    public function updateStatement(StatementDTO $statementDTO): bool
    {
        return $this->findById($statementDTO->id)
            ->update($statementDTO->toArray());
    }

    public function getSummaryByPeriod(ReportPeriod $period): SupportCollection
    {
        $query = Statement::query();

        if ($startDate = $period->getStartDate()) {
            $query->where('created_at', '>=', $startDate);
        }

        return $query->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();
    }

    public function getTrendByPeriod(ReportPeriod $period): SupportCollection
    {
        $query = Statement::query();

        if ($startDate = $period->getStartDate()) {
            $query->where('created_at', '>=', $startDate);
        }

        return $query
            ->selectRaw("TO_CHAR(created_at, 'YYYY-MM') as period, count(*) as count")
            ->groupByRaw("TO_CHAR(created_at, 'YYYY-MM')")
            ->orderByRaw('1')
            ->get();
    }

    public function createByArray(array $data): Statement
    {
        return Statement::query()->create($data);
    }
}
