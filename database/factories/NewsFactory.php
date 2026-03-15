<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'title' => ['en' => $title],
            'slug' => \Illuminate\Support\Str::slug($title).'-'.fake()->unique()->numberBetween(1, 99999),
            'description' => ['en' => fake()->paragraphs(3, true)],
            'date' => fake()->dateTimeBetween('-1 year', 'now'),
            'cover' => 'news/'.fake()->uuid().'.jpg',
            'thumbnail' => 'news/'.fake()->uuid().'.jpg',
        ];
    }
}
