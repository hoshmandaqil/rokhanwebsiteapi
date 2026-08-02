<?php

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('news index returns paginated news items', function () {
    News::factory()->count(12)->create();

    $response = $this->getJson('/api/v1/news?perPage=9&page=1');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'slug', 'title', 'description', 'content', 'date', 'img', 'thumbnail', 'link'],
            ],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ])
        ->assertJsonPath('meta.current_page', 1)
        ->assertJsonPath('meta.per_page', 9)
        ->assertJsonPath('meta.total', 12);

    expect($response->json('data'))->toHaveCount(9);
});

test('news index supports limit query', function () {
    News::factory()->count(5)->create();

    $response = $this->getJson('/api/v1/news?limit=3');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'slug', 'title', 'description', 'content', 'date', 'img', 'thumbnail', 'link'],
            ],
        ]);

    expect($response->json('data'))->toHaveCount(3);
});

test('news index validates pagination query params', function () {
    $response = $this->getJson('/api/v1/news?perPage=0&limit=invalid');

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['perPage', 'limit']);
});

test('news show returns single news item by slug', function () {
    $news = News::factory()->create([
        'title' => ['en' => 'Test News'],
        'description' => ['en' => '<p>News description</p>'],
        'slug' => 'test-news',
        'thumbnail' => 'news/test-thumbnail.jpg',
    ]);

    $response = $this->getJson('/api/v1/news/test-news');

    $response->assertOk()
        ->assertJsonPath('data.id', $news->id)
        ->assertJsonPath('data.slug', 'test-news')
        ->assertJsonPath('data.title', 'Test News')
        ->assertJsonPath('data.description', 'News description')
        ->assertJsonPath('data.link', '/news/test-news');
});

test('news show returns 404 for unknown slug', function () {
    $response = $this->getJson('/api/v1/news/non-existent-news');

    $response->assertNotFound();
});

test('news api respects locale query param', function () {
    News::factory()->create([
        'title' => [
            'en' => 'English News',
            'prs' => 'خبر دری',
        ],
        'description' => [
            'en' => '<p>English description</p>',
            'prs' => '<p>وصف دری</p>',
        ],
        'slug' => 'localized-news',
    ]);

    $responseEn = $this->getJson('/api/v1/news/localized-news?locale=en');
    $responsePrs = $this->getJson('/api/v1/news/localized-news?locale=prs');

    $responseEn->assertOk()
        ->assertJsonPath('data.title', 'English News')
        ->assertJsonPath('data.description', 'English description');

    $responsePrs->assertOk()
        ->assertJsonPath('data.title', 'خبر دری')
        ->assertJsonPath('data.description', 'وصف دری');
});

test('non-fallback api locale does not substitute English when translation is missing', function () {
    News::factory()->create([
        'title' => ['en' => 'English Only'],
        'description' => ['en' => '<p>English only body</p>'],
        'slug' => 'english-only-translation',
    ]);

    $this->getJson('/api/v1/news/english-only-translation?locale=prs')
        ->assertOk()
        ->assertJsonPath('data.title', '')
        ->assertJsonPath('data.description', '');
});
