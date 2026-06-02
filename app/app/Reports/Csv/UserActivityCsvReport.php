<?php

declare(strict_types=1);

namespace App\Reports\Csv;

use Illuminate\Support\Collection;
use Traversable;

class UserActivityCsvReport extends CsvReport
{
    public function __construct(
        private readonly Collection $users,
    ) {}

    protected static function prefix(): string
    {
        return 'user_activity_report';
    }

    protected static function headers(): array
    {
        return ['User ID', 'User name', 'User email', 'Statements count', 'Bookings count'];
    }

    protected function rows(): Traversable
    {
        foreach ($this->users as $dto) {
            yield [
                $dto->userId,
                $dto->userName,
                $dto->userEmail,
                $dto->statementsCount,
                $dto->bookingsCount,
            ];
        }
    }
}
