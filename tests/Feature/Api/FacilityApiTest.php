<?php

use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('facilities index returns list of facilities', function () {
    Facility::factory()->count(2)->create();

    $response = $this->getJson('/api/v1/facilities');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'slug', 'title', 'description', 'image'],
            ],
        ]);
    expect($response->json('data'))->toHaveCount(2);
});

test('facilities index returns empty array when no facilities', function () {
    $response = $this->getJson('/api/v1/facilities');

    $response->assertOk()
        ->assertJsonPath('data', []);
});

test('facility show returns single facility by slug', function () {
    $facility = Facility::factory()->create([
        'title' => ['en' => 'Library and Research Center'],
        'slug' => 'library-and-research-center',
    ]);

    $response = $this->getJson('/api/v1/facilities/library-and-research-center');

    $response->assertOk()
        ->assertJsonPath('data.id', $facility->id)
        ->assertJsonPath('data.slug', 'library-and-research-center')
        ->assertJsonPath('data.title', 'Library and Research Center')
        ->assertJsonStructure([
            'data' => ['id', 'slug', 'title', 'description', 'image'],
        ]);
});

test('facility show returns 404 for unknown slug', function () {
    $response = $this->getJson('/api/v1/facilities/non-existent-slug');

    $response->assertNotFound();
});

test('facility api respects locale query param', function () {
    $facility = Facility::factory()->create([
        'title' => [
            'en' => 'English Title',
            'ar' => 'العنوان بالعربية',
        ],
        'description' => [
            'en' => 'English description',
            'ar' => 'الوصف بالعربية',
        ],
        'slug' => 'test-facility',
    ]);

    $responseEn = $this->getJson('/api/v1/facilities/test-facility?locale=en');
    $responseAr = $this->getJson('/api/v1/facilities/test-facility?locale=ar');

    $responseEn->assertOk()
        ->assertJsonPath('data.title', 'English Title')
        ->assertJsonPath('data.description', 'English description');

    $responseAr->assertOk()
        ->assertJsonPath('data.title', 'العنوان بالعربية')
        ->assertJsonPath('data.description', 'الوصف بالعربية');
});
