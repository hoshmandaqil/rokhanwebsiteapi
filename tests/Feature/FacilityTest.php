<?php

use App\Models\Facility;

test('facility can be created with factory', function () {
    $facility = Facility::factory()->create([
        'title' => ['en' => 'Library and Research Center'],
    ]);

    expect($facility->getTranslation('title', 'en'))->toBe('Library and Research Center')
        ->and($facility->slug)->not->toBeEmpty()
        ->and($facility->image)->not->toBeEmpty();
});

test('facility slug is unique when saving with same title', function () {
    $facility1 = Facility::factory()->create([
        'title' => ['en' => 'Same Facility Name'],
        'slug' => '',
    ]);
    $facility2 = Facility::factory()->create([
        'title' => ['en' => 'Same Facility Name'],
        'slug' => '',
    ]);

    expect($facility1->slug)->toBe('same-facility-name')
        ->and($facility2->slug)->not->toBe($facility1->slug)
        ->and($facility2->slug)->toContain('same-facility-name');
});
