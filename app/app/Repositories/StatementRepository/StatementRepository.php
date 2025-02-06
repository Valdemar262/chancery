<?php

namespace App\Repositories\StatementRepository;

use App\Models\Statement;
use Illuminate\Database\Eloquent\Collection;

class StatementRepository
{
    public function findById(int $id): Statement
    {
        return Statement::find($id);
    }

    public function getAll(): Collection
    {
        return Statement::all();
    }

    public function destroy(int $id): int
    {
        return Statement::destroy($id);
    }
}
