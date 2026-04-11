<?php

declare(strict_types=1);

namespace App\Data\StatementDTO;

use App\Data\BaseDTO\BaseDTO;

class StatementsTrendDTO extends BaseDTO
{
    public function __construct(
        public string $period,
        public int    $count,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'period' => 'required|string',
            'count'  => 'required|int',
        ];
    }
}
