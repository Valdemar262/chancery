<?php

namespace App\Data\AllUsersDTO;

use App\Data\BaseDTO\BaseDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * @OA\Schema(
 *     schema="AllUsersDTO",
 *     title="All Users DTO",
 *     description="DTO для списка пользователей",
 *     type="object"
)
 */
class AllUsersDTO extends BaseDTO
{
    /**
     * @OA\Property(
     *     property="allUsers",
     *     type="array",
     *     description="Список пользователей",
     *     @OA\Items(ref="#/components/schemas/User")
     * )
     *
     * @param Collection<int, User> $allUsers
     */
    public function __construct(
        public Collection $allUsers,
    ) {
        parent::__construct();
    }
}
