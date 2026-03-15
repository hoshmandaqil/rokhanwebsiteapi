<?php

use App\Enums\LeadershipMessageType;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\LeadershipMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('department message can be created with a selected department', function () {
    $faculty = Faculty::factory()->create();
    $department = Department::create([
        'title' => ['en' => 'Computer Science'],
        'description' => ['en' => 'CS department'],
        'faculty_id' => $faculty->id,
    ]);

    $message = LeadershipMessage::create([
        'type' => LeadershipMessageType::Department,
        'department_id' => $department->id,
        'faculty_id' => null,
        'name' => ['en' => 'Dr. John'],
        'title' => ['en' => 'Head of Department'],
        'message' => ['en' => 'Welcome message.'],
        'image' => 'leadership/image.jpg',
        'cover' => 'leadership/cover.jpg',
    ]);

    expect($message->type)->toBe(LeadershipMessageType::Department)
        ->and($message->department_id)->toBe($department->id)
        ->and($message->faculty_id)->toBeNull()
        ->and($message->department->id)->toBe($department->id);

    $found = LeadershipMessage::forDepartment($department->id)->first();
    expect($found?->id)->toBe($message->id);
});

test('faculty message can be created with a selected faculty', function () {
    $faculty = Faculty::factory()->create();

    $message = LeadershipMessage::create([
        'type' => LeadershipMessageType::Faculty,
        'department_id' => null,
        'faculty_id' => $faculty->id,
        'name' => ['en' => 'Dr. Jane'],
        'title' => ['en' => 'Dean'],
        'message' => ['en' => 'Faculty welcome.'],
        'image' => 'leadership/image.jpg',
        'cover' => 'leadership/cover.jpg',
    ]);

    expect($message->type)->toBe(LeadershipMessageType::Faculty)
        ->and($message->faculty_id)->toBe($faculty->id)
        ->and($message->department_id)->toBeNull()
        ->and($message->faculty->id)->toBe($faculty->id);

    $found = LeadershipMessage::forFaculty($faculty->id)->first();
    expect($found?->id)->toBe($message->id);
});
