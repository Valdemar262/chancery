<?php

namespace App\Data\RefreshTokenResponseDTO;

use App\Data\BaseDTO\BaseDTO;

/**
 * @OA\Schema(
 *     schema="RefreshTokenResponseDTO",
 *     title="Refresh Token Response DTO",
 *     description="DTO для возвращаемого ответа при успешном обновлении токена, содержащий новые токены.",
 *     type="object",
 *     required={"tokenData"},
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
class RefreshTokenResponseDTO extends BaseDTO
{
    /**
     * @param array<string, string|int> $tokenData
     */
    public function __construct(
        public array $tokenData,
    ) {
        parent::__construct();
    }
}
