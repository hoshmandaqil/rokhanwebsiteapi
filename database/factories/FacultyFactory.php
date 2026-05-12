<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faculty>
 */
class FacultyFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => ['en' => fake()->sentence(4)],
            'description' => ['en' => fake()->paragraph()],
            'dean_message' => ['en' => '<p>'.fake()->paragraph().'</p>'],
            'mission_content' => ['en' => fake()->paragraph()],
            'vision_content' => ['en' => fake()->paragraph()],
            'faculty_profile_instructor_ids' => [],
            'cover' => 'faculties/'.fake()->uuid().'.jpg',
        ];
    }
}
