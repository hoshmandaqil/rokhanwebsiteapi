<?php

use App\Enums\PlanScope;
use App\Enums\PlanType;
use App\Enums\ProgramLevel;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Instructor;
use App\Models\Plan;
use App\Models\Program;
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
                    'deanMessage',
                    'vision',
                    'mission',
                    'cover',
                    'departments',
                    'degreePrograms',
                    'faculty_profile',
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
        'dean_message' => ['en' => '<p>Welcome from the dean.</p>'],
        'mission_content' => ['en' => 'Engineering mission'],
        'vision_content' => ['en' => 'Engineering vision'],
        'cover' => 'faculties/test-cover.jpg',
    ]);

    $department = Department::query()->create([
        'title' => ['en' => 'Software Engineering'],
        'description' => ['en' => 'Software program'],
        'cover' => 'departments/software.jpg',
        'faculty_id' => $faculty->id,
    ]);

    Program::query()->create([
        'department_id' => $department->id,
        'level' => ProgramLevel::Bachelor,
        'slug' => 'bsc-software',
        'title' => ['en' => 'BSc Software'],
        'description' => ['en' => '<p>Program description</p>'],
        'sort_order' => 1,
        'is_published' => true,
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

    $instructor = Instructor::factory()->create([
        'faculty_id' => $faculty->id,
        'name' => ['en' => 'Dr. Jane Instructor'],
        'title' => ['en' => 'Assistant Professor'],
        'photo' => 'instructors/jane.jpg',
    ]);

    $faculty->update([
        'faculty_profile_instructor_ids' => [$instructor->id],
    ]);

    $response = $this->getJson('/api/v1/faculties/'.$faculty->id);

    $response->assertOk()
        ->assertJsonPath('data.id', $faculty->id)
        ->assertJsonPath('data.title', 'Engineering Faculty')
        ->assertJsonPath('data.description', 'Engineering faculty description')
        ->assertJsonPath('data.deanMessage', '<p>Welcome from the dean.</p>')
        ->assertJsonPath('data.mission', 'Engineering mission')
        ->assertJsonPath('data.vision', 'Engineering vision')
        ->assertJsonPath('data.departments.0.title', 'Software Engineering')
        ->assertJsonPath('data.departments.0.faculty_id', $faculty->id)
        ->assertJsonPath('data.degreePrograms.0.slug', 'bsc-software')
        ->assertJsonPath('data.degreePrograms.0.title', 'BSc Software')
        ->assertJsonPath('data.faculty_profile.0.name', 'Dr. Jane Instructor')
        ->assertJsonPath('data.faculty_profile.0.title', 'Assistant Professor')
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
        'dean_message' => [
            'en' => '<p>English dean</p>',
            'ar' => '<p>عميد عربي</p>',
        ],
        'mission_content' => [
            'en' => 'English mission',
            'ar' => 'مهمة عربية',
        ],
        'vision_content' => [
            'en' => 'English vision',
            'ar' => 'رؤية عربية',
        ],
    ]);

    $department = Department::query()->create([
        'title' => [
            'en' => 'English Department',
            'ar' => 'قسم عربي',
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
        ->assertJsonPath('data.deanMessage', '<p>English dean</p>')
        ->assertJsonPath('data.mission', 'English mission')
        ->assertJsonPath('data.vision', 'English vision')
        ->assertJsonPath('data.departments.0.title', 'English Department')
        ->assertJsonPath('data.strategic_plans.0.title', 'English Strategic Plan');

    $responseAr->assertOk()
        ->assertJsonPath('data.title', 'كلية عربية')
        ->assertJsonPath('data.description', 'وصف عربي')
        ->assertJsonPath('data.deanMessage', '<p>عميد عربي</p>')
        ->assertJsonPath('data.mission', 'مهمة عربية')
        ->assertJsonPath('data.vision', 'رؤية عربية')
        ->assertJsonPath('data.departments.0.title', 'قسم عربي')
        ->assertJsonPath('data.strategic_plans.0.title', 'خطة استراتيجية عربية');
});
