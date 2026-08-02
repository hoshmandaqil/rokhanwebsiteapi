<?php

use App\Enums\AboutPageKey;
use App\Enums\AboutType;
use App\Models\About;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('strategic plan about page includes documents from the about form', function () {
    Storage::fake('local');
    Storage::disk('local')->put('abouts/documents/university-plan.pdf', '%PDF-1.4 test');

    About::query()->create([
        'type' => AboutType::University,
        'page_key' => AboutPageKey::StrategicPlan->value,
        'title' => ['en' => 'Strategic Plan'],
        'description' => ['en' => '<p>University strategy overview.</p>'],
        'image' => 'abouts/banner.jpg',
        'documents' => [
            [
                'title' => 'Strategic Plan 2025-2030',
                'file' => 'abouts/documents/university-plan.pdf',
            ],
            [
                'title' => 'Implementation Roadmap',
                'file' => 'abouts/documents/roadmap.pdf',
            ],
        ],
    ]);

    $response = $this->getJson('/api/v1/abouts/strategic-plan');

    $response->assertOk()
        ->assertJsonPath('data.page_key', 'strategic-plan')
        ->assertJsonPath('data.title', 'Strategic Plan')
        ->assertJsonPath('data.documents.0.title', 'Strategic Plan 2025-2030')
        ->assertJsonPath('data.documents.1.title', 'Implementation Roadmap')
        ->assertJsonCount(2, 'data.documents');
});

test('non strategic about pages do not include documents', function () {
    About::query()->create([
        'type' => AboutType::University,
        'page_key' => AboutPageKey::History->value,
        'title' => ['en' => 'History'],
        'description' => ['en' => '<p>University history.</p>'],
        'image' => 'abouts/history.jpg',
        'documents' => [
            [
                'title' => 'Should not appear',
                'file' => 'abouts/documents/hidden.pdf',
            ],
        ],
    ]);

    $response = $this->getJson('/api/v1/abouts/history');

    $response->assertOk()
        ->assertJsonPath('data.page_key', 'history')
        ->assertJsonMissingPath('data.documents');
});

test('strategic plan documents skip entries without a title', function () {
    About::query()->create([
        'type' => AboutType::University,
        'page_key' => AboutPageKey::StrategicPlan->value,
        'title' => ['en' => 'Strategic Plan'],
        'description' => ['en' => '<p>Overview</p>'],
        'image' => 'abouts/banner.jpg',
        'documents' => [
            [
                'title' => '',
                'file' => 'abouts/documents/empty-title.pdf',
            ],
            [
                'title' => 'Valid Plan',
                'file' => 'abouts/documents/valid.pdf',
            ],
        ],
    ]);

    $this->getJson('/api/v1/abouts/strategic-plan')
        ->assertOk()
        ->assertJsonCount(1, 'data.documents')
        ->assertJsonPath('data.documents.0.title', 'Valid Plan');
});
