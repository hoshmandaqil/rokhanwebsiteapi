<?php

use App\Models\Announcement;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('announcements index returns paginated announcement items', function () {
    Announcement::factory()->count(12)->create();

    $response = $this->getJson('/api/v1/announcements?perPage=9&page=1');

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

test('announcements index supports limit query', function () {
    Announcement::factory()->count(5)->create();

    $response = $this->getJson('/api/v1/announcements?limit=3');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'slug', 'title', 'description', 'content', 'date', 'img', 'cover', 'thumbnail', 'link'],
            ],
        ]);

    expect($response->json('data'))->toHaveCount(3);
});

test('announcements index validates pagination query params', function () {
    $response = $this->getJson('/api/v1/announcements?perPage=0&limit=invalid');

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['perPage', 'limit']);
});

test('announcements show returns single announcement item by slug', function () {
    $announcement = Announcement::factory()->create([
        'title' => ['en' => 'Test Announcement'],
        'description' => ['en' => '<p>Announcement description</p>'],
        'slug' => 'test-announcement',
        'cover' => 'announcements/test-cover.jpg',
        'thumbnail' => 'announcements/test-thumbnail.jpg',
    ]);

    $response = $this->getJson('/api/v1/announcements/test-announcement');

    $response->assertOk()
        ->assertJsonPath('data.id', $announcement->id)
        ->assertJsonPath('data.slug', 'test-announcement')
        ->assertJsonPath('data.title', 'Test Announcement')
        ->assertJsonPath('data.description', 'Announcement description')
        ->assertJsonPath('data.link', '/announcements/test-announcement');
});

test('announcements show returns 404 for unknown slug', function () {
    $response = $this->getJson('/api/v1/announcements/non-existent-announcement');

    $response->assertNotFound();
});

test('announcements api respects locale query param', function () {
    Announcement::factory()->create([
        'title' => [
            'en' => 'English Announcement',
            'prs' => 'اعلان دری',
        ],
        'description' => [
            'en' => '<p>English description</p>',
            'prs' => '<p>توضیح دری</p>',
        ],
        'slug' => 'localized-announcement',
    ]);

    $responseEn = $this->getJson('/api/v1/announcements/localized-announcement?locale=en');
    $responsePrs = $this->getJson('/api/v1/announcements/localized-announcement?locale=prs');

    $responseEn->assertOk()
        ->assertJsonPath('data.title', 'English Announcement')
        ->assertJsonPath('data.description', 'English description');

    $responsePrs->assertOk()
        ->assertJsonPath('data.title', 'اعلان دری')
        ->assertJsonPath('data.description', 'توضیح دری');
});
