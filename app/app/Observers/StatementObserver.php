<?php

declare(strict_types=1);

namespace App\Observers;

use App\Cache\Entities\StatementEntityCache;
use App\Models\Statement;

readonly class StatementObserver
{
    public function __construct(
        private StatementEntityCache  $statementEntityCache,
    ) {}

    public function created(Statement $statement): void
    {
        $this->statementEntityCache->forgetAll();
    }

    public function updated(Statement $statement): void
    {
        $this->statementEntityCache->forget($statement->id);
        $this->statementEntityCache->forgetAll();
    }

    public function deleted(Statement $statement): void
    {
        $this->statementEntityCache->forget($statement->id);
        $this->statementEntityCache->forgetAll();
    }

    public function restored(Statement $statement): void
    {
        //
    }

    public function forceDeleted(Statement $statement): void
    {
        //
    }
}
