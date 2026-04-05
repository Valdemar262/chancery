<?php

declare(strict_types=1);

namespace App\CustomObservers;

use App\Models\Statement;
use Illuminate\Support\Facades\Log;

class CustomStatementObserver
{
    public function approved(Statement $statement): void
    {
        Log::info('Statement observer execute method: approved');
    }

    public function submitted(Statement $statement): void
    {
        Log::info('Statement observer execute method: submitted');
    }

    public function rejected(Statement $statement): void
    {
        Log::info('Statement observer execute method: rejected');
    }
}
