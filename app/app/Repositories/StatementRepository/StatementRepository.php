<?php

namespace App\Repositories\StatementRepository;

use App\Models\Statement;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Optional;

class StatementRepository
{
    public function findById(int|Optional $id): Statement
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
}
