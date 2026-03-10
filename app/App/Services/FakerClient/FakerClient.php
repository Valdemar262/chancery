<?php

namespace App\Services\FakerClient;

class FakerClient
{
    public function __construct()
    {
        $this->faker = \Faker\Factory::create('ru_RU');
    }

    public function __call(string $name, array $arguments): mixed
    {
        return $this->faker->$name(...$arguments);
    }
}
