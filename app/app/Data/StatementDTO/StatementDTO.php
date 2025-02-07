<?php

namespace App\Data\StatementDTO;

use App\Data\BaseDTO\BaseDTO;
use Spatie\LaravelData\Optional;

/**
 * @OA\Schema(
 *     schema="StatementDTO",
 *     title="Statement DTO",
 *     description="DTO для заявления, которое используется для передачи данных",
 *     type="object",
 *     required={"title", "user_id", "number"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID заявления",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Заголовок заявления",
 *         minLength=5
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         description="ID пользователя, к которому относится заявление",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="number",
 *         type="integer",
 *         description="Номер заявления",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date",
 *         description="Дата подачи заявления",
 *         nullable=true
 *     )
 * )
 */
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
