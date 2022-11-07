<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Container>
 */
class ContainerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'number' => $this->faker->unique()->regexify("[A-Z0-9]{9}"),
            'seal' => $this->faker->regexify("[A-Z0-9]{5}"),
            'tare' => $this->faker->numberBetween(3000, 4000)
        ];
    }
}
