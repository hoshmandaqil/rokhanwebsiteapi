<?php

use App\Enums\PlanScope;
use App\Enums\PlanType;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('faculties index returns faculty items', function () {
    Faculty::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/faculties');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'overview',
                    'cover',
                    'programs' => [
                        '*' => ['id', 'title', 'description', 'cover', 'faculty_id', 'created_at', 'updated_at'],
                    ],
                    'strategic_plans',
                ],
            ],
        ]);

    expect($response->json('data'))->toHaveCount(3);
});

test('faculties show returns single faculty item by id', function () {
    $faculty = Faculty::factory()->create([
        'title' => ['en' => 'Engineering Faculty'],
        'description' => ['en' => 'Engineering faculty description'],
        'cover' => 'faculties/test-cover.jpg',
    ]);

    Department::query()->create([
        'title' => ['en' => 'Software Engineering'],
        'description' => ['en' => 'Software program'],
        'cover' => 'departments/software.jpg',
        'faculty_id' => $faculty->id,
    ]);

    Plan::factory()->create([
        'scope' => PlanScope::Faculty,
        'type' => PlanType::StrategicPlan,
        'faculty_id' => $faculty->id,
        'department_id' => null,
        'title' => ['en' => 'Faculty Strategic Plan'],
        'description' => ['en' => 'Faculty strategy'],
        'slug' => 'faculty-strategic-plan',
        'poster' => 'plans/strategic-poster.jpg',
        'file' => 'plans/strategic.pdf',
    ]);

    $response = $this->getJson('/api/v1/faculties/'.$faculty->id);

    $response->assertOk()
        ->assertJsonPath('data.id', $faculty->id)
        ->assertJsonPath('data.title', 'Engineering Faculty')
        ->assertJsonPath('data.description', 'Engineering faculty description')
        ->assertJsonPath('data.overview', 'Engineering faculty description')
        ->assertJsonPath('data.programs.0.title', 'Software Engineering')
        ->assertJsonPath('data.programs.0.faculty_id', $faculty->id)
        ->assertJsonPath('data.strategic_plans.0.title', 'Faculty Strategic Plan');
});

test('faculties show returns 404 for unknown id', function () {
    $response = $this->getJson('/api/v1/faculties/999999');

    $response->assertNotFound();
});

test('faculties api respects locale query param', function () {
    $faculty = Faculty::factory()->create([
        'title' => [
            'en' => 'English Faculty',
            'ar' => 'كلية عربية',
        ],
        'description' => [
            'en' => 'English description',
            'ar' => 'وصف عربي',
        ],
    ]);

    Department::query()->create([
        'title' => [
            'en' => 'English Program',
            'ar' => 'برنامج عربي',
        ],
        'description' => [
            'en' => 'English program description',
            'ar' => 'وصف برنامج عربي',
        ],
        'cover' => 'departments/program.jpg',
        'faculty_id' => $faculty->id,
    ]);

    Plan::factory()->create([
        'scope' => PlanScope::Faculty,
        'type' => PlanType::StrategicPlan,
        'faculty_id' => $faculty->id,
        'department_id' => null,
        'title' => [
            'en' => 'English Strategic Plan',
            'ar' => 'خطة استراتيجية عربية',
        ],
        'description' => [
            'en' => 'English strategic description',
            'ar' => 'وصف استراتيجي عربي',
        ],
        'slug' => 'localized-strategic-plan',
    ]);

    $responseEn = $this->getJson('/api/v1/faculties/'.$faculty->id.'?locale=en');
    $responseAr = $this->getJson('/api/v1/faculties/'.$faculty->id.'?locale=ar');

    $responseEn->assertOk()
        ->assertJsonPath('data.title', 'English Faculty')
        ->assertJsonPath('data.description', 'English description')
        ->assertJsonPath('data.overview', 'English description')
        ->assertJsonPath('data.programs.0.title', 'English Program')
        ->assertJsonPath('data.strategic_plans.0.title', 'English Strategic Plan');

    $responseAr->assertOk()
        ->assertJsonPath('data.title', 'كلية عربية')
        ->assertJsonPath('data.description', 'وصف عربي')
        ->assertJsonPath('data.overview', 'وصف عربي')
        ->assertJsonPath('data.programs.0.title', 'برنامج عربي')
        ->assertJsonPath('data.strategic_plans.0.title', 'خطة استراتيجية عربية');
});
