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

        $courseNames = [
            'Introducción a la Programación',
            'Desarrollo Web con HTML y CSS',
            'Bases de Datos Relacionales',
            'Inteligencia Artificial y Aprendizaje Automático',
            'Diseño Gráfico y Multimedia',
            'Inglés para Negocios',
            'Gestión de Proyectos Ágiles',
            'Marketing Digital',
            'Fundamentos de Finanzas',
            'Gestión del Talento Humano',
            'Inglés A1',
            'Inglés A2',
            'Inglés B1',
            'Inglés B2'
        ];

        return [
            'name' => $faker->unique()->randomElement($courseNames),
            'description' => $faker->paragraph,
            'number_hours' => $faker->numberBetween(10, 100),
            'cost' => $faker->randomFloat(2, 50, 500),
        ];
    }
}
