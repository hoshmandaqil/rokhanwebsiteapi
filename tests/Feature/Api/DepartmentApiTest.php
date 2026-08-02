<?php

use App\Enums\ProgramLevel;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('departments index returns department items', function () {
    $faculty = Faculty::factory()->create();

    Department::query()->create([
        'title' => ['en' => 'Computer Science'],
        'description' => ['en' => 'CS department'],
        'vision_content' => ['en' => '<p>CS vision</p>'],
        'mission_content' => ['en' => '<p>CS mission</p>'],
        'cover' => 'departments/cs.jpg',
        'faculty_id' => $faculty->id,
    ]);

    $response = $this->getJson('/api/v1/departments');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'slug',
                    'title',
                    'description',
                    'vision',
                    'mission',
                    'cover',
                    'href',
                    'faculty_id',
                    'faculty',
                    'programs',
                ],
            ],
        ]);

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.title'))->toBe('Computer Science');
    expect($response->json('data.0.slug'))->toBe('computer-science');
    expect($response->json('data.0.href'))->toBe('/departments/computer-science');
});

test('departments show returns single department by slug with nested data', function () {
    $faculty = Faculty::factory()->create([
        'title' => ['en' => 'Engineering Faculty'],
        'cover' => 'faculties/eng.jpg',
    ]);

    $department = Department::query()->create([
        'title' => ['en' => 'Software Engineering'],
        'description' => ['en' => '<p>Software department</p>'],
        'vision_content' => ['en' => '<p>Software vision</p>'],
        'mission_content' => ['en' => '<p>Software mission</p>'],
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

    Program::query()->create([
        'department_id' => $department->id,
        'level' => ProgramLevel::Bachelor,
        'slug' => 'unpublished-software',
        'title' => ['en' => 'Unpublished Software'],
        'description' => ['en' => '<p>Hidden</p>'],
        'sort_order' => 2,
        'is_published' => false,
    ]);

    $response = $this->getJson('/api/v1/departments/software-engineering');

    $response->assertOk()
        ->assertJsonPath('data.id', $department->id)
        ->assertJsonPath('data.slug', 'software-engineering')
        ->assertJsonPath('data.title', 'Software Engineering')
        ->assertJsonPath('data.description', '<p>Software department</p>')
        ->assertJsonPath('data.vision', '<p>Software vision</p>')
        ->assertJsonPath('data.mission', '<p>Software mission</p>')
        ->assertJsonPath('data.faculty.title', 'Engineering Faculty')
        ->assertJsonPath('data.faculty.href', '/faculties/'.$faculty->id)
        ->assertJsonPath('data.programs.0.slug', 'bsc-software')
        ->assertJsonPath('data.programs.0.title', 'BSc Software');

    expect($response->json('data.programs'))->toHaveCount(1);
});

test('departments show returns 404 for unknown slug', function () {
    $response = $this->getJson('/api/v1/departments/does-not-exist');

    $response->assertNotFound();
});

test('departments api respects locale query param', function () {
    $faculty = Faculty::factory()->create();

    Department::query()->create([
        'title' => [
            'en' => 'English Department',
            'prs' => 'دیپارتمنت دری',
        ],
        'description' => [
            'en' => 'English description',
            'prs' => 'توضیح دری',
        ],
        'vision_content' => [
            'en' => 'English vision',
            'prs' => 'بینش دری',
        ],
        'mission_content' => [
            'en' => 'English mission',
            'prs' => 'ماموریت دری',
        ],
        'cover' => 'departments/localized.jpg',
        'faculty_id' => $faculty->id,
    ]);

    $responseEn = $this->getJson('/api/v1/departments?locale=en');
    $responsePrs = $this->getJson('/api/v1/departments?locale=prs');

    $responseEn->assertOk()
        ->assertJsonPath('data.0.title', 'English Department')
        ->assertJsonPath('data.0.description', 'English description')
        ->assertJsonPath('data.0.vision', 'English vision')
        ->assertJsonPath('data.0.mission', 'English mission');

    $responsePrs->assertOk()
        ->assertJsonPath('data.0.title', 'دیپارتمنت دری')
        ->assertJsonPath('data.0.description', 'توضیح دری')
        ->assertJsonPath('data.0.vision', 'بینش دری')
        ->assertJsonPath('data.0.mission', 'ماموریت دری');
});
