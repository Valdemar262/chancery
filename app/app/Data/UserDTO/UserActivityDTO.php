<?php

declare(strict_types=1);

namespace App\Data\UserDTO;

use App\Data\BaseDTO\BaseDTO;

class UserActivityDTO extends BaseDTO
{
    public function __construct(
        public int    $userId,
        public string $userName,
        public string $userEmail,
        public int    $statementsCount,
        public int    $bookingsCount,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'userId'          => ['required', 'integer', 'exists:users,id'],
            'userName'        => ['required', 'string', 'max:255'],
            'userEmail'       => ['required', 'email', 'string', 'max:255'],
            'statementsCount' => ['required', 'integer'],
            'bookingsCount'   => ['required', 'integer'],
        ];
    }
}
