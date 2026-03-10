<?php

namespace App\Data\ResourceDTO;

use App\Data\BaseDTO\BaseDTO;

class ResourceDTO extends BaseDTO
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $type,
        public ?string $description,
    ) {
        parent::__construct();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'type' => ['required', 'min:4', 'max:255'],
        ];
    }
}
