<?php

declare(strict_types=1);

namespace App\Reports\Csv;

use Illuminate\Support\Collection;
use Traversable;

class BookingsByResourceCsvReport extends CsvReport
{
    public function __construct(
        private readonly Collection $bookings,
    ) {}

    protected static function prefix(): string
    {
        return 'booking_by_resource_report';
    }

    protected static function headers(): array
    {
        return ['Resource', 'Bookings count'];
    }

    protected function rows(): Traversable
    {
        foreach ($this->bookings as $dto) {
            yield [$dto->resourceName, $dto->count];
        }
    }
}
