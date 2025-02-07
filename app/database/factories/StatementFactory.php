<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->name(),
            'user_id' => User::factory(),
            'number' => $this->faker->randomNumber(),
            'date' => $this->faker->date(),
        ];
    }
}
