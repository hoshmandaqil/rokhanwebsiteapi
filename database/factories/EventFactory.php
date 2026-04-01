<?php

namespace Database\Factories;

use App\Enums\EventType;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startAt = fake()->dateTimeBetween('now', '+3 months');
        $endAt = fake()->optional(0.7)->dateTimeBetween($startAt, (clone $startAt)->modify('+3 days'));
        $title = fake()->sentence(4);

        return [
            'type' => fake()->randomElement(EventType::cases()),
            'title' => ['en' => $title],
            'slug' => \Illuminate\Support\Str::slug($title).'-'.fake()->unique()->numberBetween(1, 99999),
            'description' => ['en' => fake()->paragraphs(3, true)],
            'location' => ['en' => fake()->optional(0.8)->address()],
            'start_at' => $startAt,
            'end_at' => $endAt,
            'cover' => 'events/'.fake()->uuid().'.jpg',
            'thumbnail' => 'events/'.fake()->uuid().'.jpg',
            'is_published' => fake()->boolean(70),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => ['is_published' => true]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => ['is_published' => false]);
    }
}
