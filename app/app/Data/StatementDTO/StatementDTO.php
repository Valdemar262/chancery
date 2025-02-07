<?php

namespace App\Data\StatementDTO;

use App\Data\BaseDTO\BaseDTO;
use Spatie\LaravelData\Optional;

class StatementDTO extends BaseDTO
{
    public function __construct(
        public int|Optional $id,
        public string       $title,
        public int|Optional $user_id,
        public int|Optional $number,
        public ?string      $date,
    ) {
        parent::__construct();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public static function rules(): array
    {
        return [
            'title' => ['required|min:5'],
            'user_id' => ['required|int'],
            'number' => ['required|int'],
            'date' => ['date'],
        ];
    }
}
