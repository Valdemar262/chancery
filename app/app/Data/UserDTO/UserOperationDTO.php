<?php

namespace App\Data\UserDTO;

use App\Data\BaseDTO\BaseDTO;


/**
 * @OA\Schema(
 *     schema="UserOperationDTO",
 *     title="User Operation DTO",
 *     description="DTO для операции с пользователем",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID пользователя"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Имя пользователя"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="Email пользователя"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         description="Телефон пользователя",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="string",
 *         description="Адрес пользователя",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="birthday",
 *         type="string",
 *         format="date",
 *         description="Дата рождения пользователя",
 *         nullable=true
 *     )
 * )
 */
class UserOperationDTO extends BaseDTO
{
    public function __construct(
        public int     $id,
        public string  $name,
        public string  $email,
        public ?string $phone,
        public ?string $address,
        public ?string $birthday,
    ) {
        parent::__construct();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public static function rules(): array
    {
        return [
            'email' => ['email'],
        ];
    }
}
