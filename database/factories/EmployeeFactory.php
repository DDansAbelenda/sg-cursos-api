<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr; 

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $faker = \Faker\Factory::create('es_ES');
        $nationalities = ['Cubana', 'Española', 'Americana'];

        $dateOfBirth = $faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d');

        return [
            'name' => $faker->firstName,
            'last_names' => $faker->lastName . ' ' . $faker->lastName,
            'address' => $faker->address,
            'phone' => $faker->numerify('########'),
            'nif' => $faker->unique()->numerify('##########'),
            'date_birth' => $dateOfBirth,
            'nationality' => Arr::random($nationalities),
            'salary' => $faker->randomFloat(2, 1000, 10000),
            'sex' => $faker->randomElement(['Masculino', 'Femenino']),
            'is_qualified' => $faker->boolean,
        ];
    }
}
