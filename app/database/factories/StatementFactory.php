<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Statement;

/**
 * @extends Factory<Statement>
 */
class StatementFactory extends Factory
{
    /**
     *@return array<string, mixed>
     */
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
