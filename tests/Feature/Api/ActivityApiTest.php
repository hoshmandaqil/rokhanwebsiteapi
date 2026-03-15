<?php

use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('activities index returns paginated activity items', function () {
    Activity::factory()->count(12)->create();

    $response = $this->getJson('/api/v1/activities?perPage=9&page=1');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'slug', 'title', 'description', 'content', 'date', 'img', 'cover', 'thumbnail', 'link'],
            ],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ])
        ->assertJsonPath('meta.current_page', 1)
        ->assertJsonPath('meta.per_page', 9)
        ->assertJsonPath('meta.total', 12);

    expect($response->json('data'))->toHaveCount(9);
});

test('activities index supports limit query', function () {
    Activity::factory()->count(5)->create();

    $response = $this->getJson('/api/v1/activities?limit=3');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'slug', 'title', 'description', 'content', 'date', 'img', 'cover', 'thumbnail', 'link'],
            ],
        ]);

    expect($response->json('data'))->toHaveCount(3);
});

test('activities index validates pagination query params', function () {
    $response = $this->getJson('/api/v1/activities?perPage=0&limit=invalid');

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['perPage', 'limit']);
});

test('activities show returns single activity item by slug', function () {
    $activity = Activity::factory()->create([
        'title' => ['en' => 'Test Activity'],
        'description' => ['en' => '<p>Activity description</p>'],
        'slug' => 'test-activity',
        'cover' => 'activities/test-cover.jpg',
        'thumbnail' => 'activities/test-thumbnail.jpg',
    ]);

    $response = $this->getJson('/api/v1/activities/test-activity');

    $response->assertOk()
        ->assertJsonPath('data.id', $activity->id)
        ->assertJsonPath('data.slug', 'test-activity')
        ->assertJsonPath('data.title', 'Test Activity')
        ->assertJsonPath('data.description', 'Activity description')
        ->assertJsonPath('data.link', '/activities/test-activity');
});

test('activities show returns 404 for unknown slug', function () {
    $response = $this->getJson('/api/v1/activities/non-existent-activity');

    $response->assertNotFound();
});

test('activities api respects locale query param', function () {
    Activity::factory()->create([
        'title' => [
            'en' => 'English Activity',
            'ar' => 'نشاط عربي',
        ],
        'description' => [
            'en' => '<p>English description</p>',
            'ar' => '<p>وصف عربي</p>',
        ],
        'slug' => 'localized-activity',
    ]);

    $responseEn = $this->getJson('/api/v1/activities/localized-activity?locale=en');
    $responseAr = $this->getJson('/api/v1/activities/localized-activity?locale=ar');

    $responseEn->assertOk()
        ->assertJsonPath('data.title', 'English Activity')
        ->assertJsonPath('data.description', 'English description');

    $responseAr->assertOk()
        ->assertJsonPath('data.title', 'نشاط عربي')
        ->assertJsonPath('data.description', 'وصف عربي');
});
