<?php

use App\Enums\EventType;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('events index returns paginated published events', function () {
    Event::factory()->count(3)->draft()->create();
    Event::factory()->count(12)->published()->create();

    $response = $this->getJson('/api/v1/events?perPage=9&page=1');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'slug', 'type', 'title', 'description', 'content', 'location', 'start_at', 'end_at', 'img', 'cover', 'thumbnail', 'link'],
            ],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ])
        ->assertJsonPath('meta.current_page', 1)
        ->assertJsonPath('meta.per_page', 9)
        ->assertJsonPath('meta.total', 12);

    expect($response->json('data'))->toHaveCount(9);
});

test('events index supports limit and type query', function () {
    Event::factory()->count(2)->published()->create(['type' => EventType::Seminar]);
    Event::factory()->count(3)->published()->create(['type' => EventType::Workshop]);

    $response = $this->getJson('/api/v1/events?limit=2&type=workshop');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'slug', 'type', 'title', 'description', 'content', 'location', 'start_at', 'end_at', 'img', 'cover', 'thumbnail', 'link'],
            ],
        ]);

    expect($response->json('data'))->toHaveCount(2);
    expect(collect($response->json('data'))->pluck('type')->unique()->all())->toBe(['workshop']);
});

test('events index validates pagination and type query params', function () {
    $response = $this->getJson('/api/v1/events?perPage=0&limit=invalid&type=unknown');

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['perPage', 'limit', 'type']);
});

test('events show returns a single published event by slug', function () {
    $event = Event::factory()->published()->create([
        'type' => EventType::Conference,
        'title' => ['en' => 'Test Event'],
        'slug' => 'test-event',
        'description' => ['en' => '<p>Event description</p>'],
        'location' => ['en' => 'Main Hall'],
        'cover' => 'events/test-cover.jpg',
        'thumbnail' => 'events/test-thumbnail.jpg',
    ]);

    $response = $this->getJson('/api/v1/events/'.$event->slug);

    $response->assertOk()
        ->assertJsonPath('data.id', $event->id)
        ->assertJsonPath('data.slug', 'test-event')
        ->assertJsonPath('data.type', 'conference')
        ->assertJsonPath('data.title', 'Test Event')
        ->assertJsonPath('data.description', 'Event description')
        ->assertJsonPath('data.location', 'Main Hall')
        ->assertJsonPath('data.link', '/events/test-event');
});

test('events show returns 404 for unknown or draft event slug', function () {
    $draft = Event::factory()->draft()->create([
        'slug' => 'draft-event',
    ]);

    $notFoundResponse = $this->getJson('/api/v1/events/non-existent-event');
    $draftResponse = $this->getJson('/api/v1/events/'.$draft->slug);

    $notFoundResponse->assertNotFound();
    $draftResponse->assertNotFound();
});

test('events api respects locale query param', function () {
    $event = Event::factory()->published()->create([
        'title' => [
            'en' => 'English Event',
            'ar' => 'فعالية عربية',
        ],
        'slug' => 'localized-event',
        'description' => [
            'en' => '<p>English description</p>',
            'ar' => '<p>وصف عربي</p>',
        ],
        'location' => [
            'en' => 'English Location',
            'ar' => 'موقع عربي',
        ],
    ]);

    $responseEn = $this->getJson('/api/v1/events/'.$event->slug.'?locale=en');
    $responseAr = $this->getJson('/api/v1/events/'.$event->slug.'?locale=ar');

    $responseEn->assertOk()
        ->assertJsonPath('data.title', 'English Event')
        ->assertJsonPath('data.description', 'English description')
        ->assertJsonPath('data.location', 'English Location');

    $responseAr->assertOk()
        ->assertJsonPath('data.title', 'فعالية عربية')
        ->assertJsonPath('data.description', 'وصف عربي')
        ->assertJsonPath('data.location', 'موقع عربي');
});
