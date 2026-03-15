<?php

use App\Enums\EventType;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('event can be created with required fields', function () {
    $event = Event::factory()->create([
        'type' => EventType::Conference,
        'title' => ['en' => 'Tech Conference 2026'],
        'description' => ['en' => 'Annual tech conference.'],
        'start_at' => now()->addWeek(),
        'cover' => 'events/cover.jpg',
        'thumbnail' => 'events/thumb.jpg',
        'is_published' => true,
    ]);

    expect($event->type)->toBe(EventType::Conference)
        ->and($event->getTranslation('title', 'en'))->toBe('Tech Conference 2026')
        ->and($event->is_published)->toBeTrue();
});

test('published scope returns only published events', function () {
    Event::factory()->draft()->create();
    $published = Event::factory()->published()->create();

    $result = Event::published()->get();

    expect($result)->toHaveCount(1)
        ->and($result->first()->id)->toBe($published->id);
});
