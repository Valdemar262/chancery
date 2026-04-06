<?php

declare(strict_types=1);

namespace App\CustomObservers;

use App\Cache\Entities\StatementEntityCache;
use App\Models\Statement;
use Illuminate\Support\Facades\Log;

readonly class CustomStatementObserver
{
    public function __construct(
        private StatementEntityCache $statementEntityCache,
    ) {}

    public function approved(Statement $statement): void
    {
        $this->statementEntityCache->forget($statement->id);
        Log::info('Statement observer execute method: approved');
    }

    public function submitted(Statement $statement): void
    {
        $this->statementEntityCache->forget($statement->id);
        Log::info('Statement observer execute method: submitted');
    }

    public function rejected(Statement $statement): void
    {
        $this->statementEntityCache->forget($statement->id);
        Log::info('Statement observer execute method: rejected');
    }
}
