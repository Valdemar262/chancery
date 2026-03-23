<?php

declare(strict_types=1);

namespace App\Data\BookingDTO;

use App\Data\BaseDTO\BaseDTO;

class BookingsByResourceDTO extends BaseDTO
{
    public function __construct(
        public int    $resourceId,
        public string $resourceName,
        public int    $count,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'resourceId'   => 'required|int',
            'resourceName' => 'required|string',
            'count'        => 'required|int',
        ];
    }
}
