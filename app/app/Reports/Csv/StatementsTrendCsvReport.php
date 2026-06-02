<?php

declare(strict_types=1);

namespace App\Reports\Csv;

use Illuminate\Support\Collection;
use Traversable;

class StatementsTrendCsvReport extends CsvReport
{
    public function __construct(
        private readonly Collection $statements,
    ) {}

    protected static function prefix(): string
    {
        return 'statements_trend_report';
    }

    protected static function headers(): array
    {
        return ['Period', 'Statements count'];
    }

    protected function rows(): Traversable
    {
        foreach ($this->statements as $dto) {
            yield [$dto->period, $dto->count];
        }
    }
}
