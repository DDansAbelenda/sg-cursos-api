<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {   
        $faker = \Faker\Factory::create('es_ES');

        return [
            'name' => $faker->name,
            'description' => $faker->paragraph,
            'number_hours' => $faker->numberBetween(10, 100),
            'cost' => $faker->randomFloat(2, 50, 500),
        ];
    }
}
