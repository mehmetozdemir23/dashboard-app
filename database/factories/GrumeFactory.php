<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grume>
 */
class GrumeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'number'=>$this->faker->unique()->numberBetween(0,1000),
            'length'=>$this->faker->numberBetween(5,10),
            'diameter'=>$this->faker->numberBetween(20,60),
        ];
    }
}
