<?php

namespace Database\Factories;

use App\Enums\PlanScope;
use App\Enums\PlanType;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'type' => fake()->randomElement(PlanType::cases()),
            'scope' => fake()->randomElement(PlanScope::cases()),
            'faculty_id' => null,
            'department_id' => null,
            'title' => ['en' => $title],
            'description' => ['en' => fake()->paragraphs(2, true)],
            'slug' => \Illuminate\Support\Str::slug($title).'-'.fake()->unique()->numberBetween(1, 99999),
            'poster' => 'plans/'.fake()->uuid().'.jpg',
            'file' => 'plans/'.fake()->uuid().'.pdf',
        ];
    }

    public function forFaculty(?Faculty $faculty = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'scope' => PlanScope::Faculty,
            'faculty_id' => $faculty?->id ?? Faculty::factory()->create()->id,
            'department_id' => null,
        ]);
    }

    public function forDepartment(?Department $department = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'scope' => PlanScope::Department,
            'faculty_id' => null,
            'department_id' => $department?->id,
        ]);
    }

    public function general(): static
    {
        return $this->state(fn (array $attributes): array => [
            'scope' => PlanScope::General,
            'faculty_id' => null,
            'department_id' => null,
        ]);
    }

    public function research(): static
    {
        return $this->state(fn (array $attributes): array => [
            'scope' => PlanScope::Research,
            'faculty_id' => null,
            'department_id' => null,
        ]);
    }
}
