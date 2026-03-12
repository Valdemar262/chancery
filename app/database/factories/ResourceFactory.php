<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    /**
     * @extends Factory<Resource>
     */
    public function definition(): array
    {
        return [
            'name'        => $this->faker->name(),
            'type'        => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
