<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faculty>
 */
class FacultyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => ['en' => fake()->sentence(4)],
            'description' => ['en' => fake()->paragraph()],
            'cover' => 'faculties/'.fake()->uuid().'.jpg',
        ];
    }
}
