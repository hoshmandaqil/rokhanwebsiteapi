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

test('news show returns single news item by slug', function () {
    $news = News::factory()->create([
        'title' => ['en' => 'Test News'],
        'description' => ['en' => '<p>News description</p>'],
        'slug' => 'test-news',
        'cover' => 'news/test-cover.jpg',
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
            'ar' => 'خبر عربي',
        ],
        'description' => [
            'en' => '<p>English description</p>',
            'ar' => '<p>وصف عربي</p>',
        ],
        'slug' => 'localized-news',
    ]);

    $responseEn = $this->getJson('/api/v1/news/localized-news?locale=en');
    $responseAr = $this->getJson('/api/v1/news/localized-news?locale=ar');

    $responseEn->assertOk()
        ->assertJsonPath('data.title', 'English News')
        ->assertJsonPath('data.description', 'English description');

    $responseAr->assertOk()
        ->assertJsonPath('data.title', 'خبر عربي')
        ->assertJsonPath('data.description', 'وصف عربي');
});
