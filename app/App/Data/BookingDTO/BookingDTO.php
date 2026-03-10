<?php

namespace App\Data\BookingDTO;

use App\Data\BaseDTO\BaseDTO;

class BookingDTO extends BaseDTO
{
    public function __construct(
        public ?int   $id = null,
        public int    $resource_id,
        public int    $user_id,
        public string $start_time,
        public string $end_time,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'resource_id' => ['required'],
            'user_id' => ['required'],
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s',
        ];
    }
}
