<?php

namespace App\Data\AuthResponseDTO;

use App\Data\BaseDTO\BaseDTO;
use App\Models\User;

/**
 * @OA\Schema(
 *     schema="AuthResponseDTO",
 *     title="Auth Response DTO",
 *     description="DTO для возвращаемого ответа при успешной аутентификации, содержащий данные пользователя и токены.",
 *     type="object",
 *     required={"user", "tokenData"},
 *     @OA\Property(
 *         property="user",
 *         description="Информация о пользователе",
 *         ref="#/components/schemas/User"
 *     ),
 *     @OA\Property(
 *         property="tokenData",
 *         description="Токены для доступа и обновления",
 *         type="object",
 *         additionalProperties={
 *             @OA\Property(type="string", description="Токен доступа")
 *         }
 *     )
 * )
 */
class AuthResponseDTO extends BaseDTO
{
    /**
     * @param array<string, string|int> $tokenData
     */
    public function __construct(
        public User   $user,
        public array $tokenData,
    ) {
        parent::__construct();
    }
}
