<?php

use App\Enums\PlanScope;
use App\Enums\PlanType;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Plan;

test('plan can be created with factory for general scope', function () {
    $plan = Plan::factory()->general()->create([
        'title' => ['en' => 'University Strategic Plan 2025'],
    ]);

    expect($plan->scope)->toBe(PlanScope::General)
        ->and($plan->faculty_id)->toBeNull()
        ->and($plan->department_id)->toBeNull()
        ->and($plan->getTranslation('title', 'en'))->toBe('University Strategic Plan 2025')
        ->and($plan->slug)->not->toBeEmpty();
});

test('plan can be created for faculty', function () {
    $faculty = Faculty::factory()->create();
    $plan = Plan::factory()->forFaculty($faculty)->create();

    expect($plan->scope)->toBe(PlanScope::Faculty)
        ->and($plan->faculty_id)->toBe($faculty->id)
        ->and($plan->department_id)->toBeNull()
        ->and($plan->faculty->id)->toBe($faculty->id);
});

test('plan can be created for department when department is provided', function () {
    $faculty = Faculty::factory()->create();
    $department = Department::create([
        'title' => ['en' => 'Computer Science'],
        'description' => ['en' => 'CS department'],
        'faculty_id' => $faculty->id,
    ]);

    $plan = Plan::factory()->forDepartment($department)->create();

    expect($plan->scope)->toBe(PlanScope::Department)
        ->and($plan->department_id)->toBe($department->id)
        ->and($plan->faculty_id)->toBeNull()
        ->and($plan->department->id)->toBe($department->id);
});

test('plan slug is unique when saving with same title', function () {
    $plan1 = Plan::factory()->general()->create([
        'title' => ['en' => 'Same Title'],
        'slug' => '',
    ]);
    $plan2 = Plan::factory()->general()->create([
        'title' => ['en' => 'Same Title'],
        'slug' => '',
    ]);

    expect($plan1->slug)->toBe('same-title')
        ->and($plan2->slug)->not->toBe($plan1->slug)
        ->and($plan2->slug)->toContain('same-title');
});

test('plan type and scope are cast to enums', function () {
    $plan = Plan::factory()->create([
        'type' => PlanType::StrategicPlan,
        'scope' => PlanScope::Research,
    ]);

    expect($plan->type)->toBe(PlanType::StrategicPlan)
        ->and($plan->scope)->toBe(PlanScope::Research);
});
