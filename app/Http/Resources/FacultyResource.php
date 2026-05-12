<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Throwable;

/** @mixin \App\Models\Faculty */
class FacultyResource extends JsonResource
{
    private function locale(): string
    {
        return request()->attributes->get('api_locale', config('app.fallback_locale', 'en'));
    }

    private function toAbsoluteUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');

        if (str_starts_with($normalizedPath, 'storage/')) {
            return url('/'.$normalizedPath);
        }

        if (Storage::disk('public')->exists($normalizedPath)) {
            return url(Storage::disk('public')->url($normalizedPath));
        }

        if (Storage::disk('local')->exists($normalizedPath)) {
            try {
                return Storage::disk('local')->temporaryUrl($normalizedPath, now()->addMinutes(30));
            } catch (Throwable) {
                return null;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = $this->locale();
        $fallback = config('app.fallback_locale', 'en');

        $title = $this->getTranslation('title', $locale)
            ?? $this->getTranslation('title', $fallback)
            ?? (is_string($this->title) ? $this->title : '');

        $description = $this->getTranslation('description', $locale)
            ?? $this->getTranslation('description', $fallback)
            ?? (is_string($this->description) ? $this->description : '');

        $deanMessage = $this->getTranslation('dean_message', $locale)
            ?? $this->getTranslation('dean_message', $fallback)
            ?? '';

        $mission = $this->getTranslation('mission_content', $locale)
            ?? $this->getTranslation('mission_content', $fallback)
            ?? '';

        $vision = $this->getTranslation('vision_content', $locale)
            ?? $this->getTranslation('vision_content', $fallback)
            ?? '';

        $departments = $this->departments->map(function ($department) use ($locale, $fallback): array {
            $deptTitle = $department->getTranslation('title', $locale)
                ?? $department->getTranslation('title', $fallback)
                ?? (is_string($department->title) ? $department->title : '');

            $programDescription = $department->getTranslation('description', $locale)
                ?? $department->getTranslation('description', $fallback)
                ?? (is_string($department->description) ? $department->description : '');

            return [
                'id' => $department->id,
                'title' => $deptTitle,
                'description' => $programDescription,
                'cover' => $this->toAbsoluteUrl($department->cover),
                'faculty_id' => $department->faculty_id,
                'created_at' => $department->created_at?->toIso8601String(),
                'updated_at' => $department->updated_at?->toIso8601String(),
            ];
        })->values()->all();

        $degreePrograms = collect();
        foreach ($this->departments as $department) {
            foreach ($department->programs as $program) {
                if (! $program->is_published) {
                    continue;
                }
                $degreePrograms[$program->id] = $program;
            }
        }

        $degreeProgramsPayload = $degreePrograms
            ->values()
            ->sort(function ($a, $b): int {
                $order = ($a->sort_order ?? 0) <=> ($b->sort_order ?? 0);

                return $order !== 0 ? $order : $a->id <=> $b->id;
            })
            ->values()
            ->map(fn ($program) => (new ProgramResource($program))->toArray($request))
            ->all();

        $strategicPlans = $this->strategicPlans->map(function ($plan) use ($locale, $fallback): array {
            $planTitle = $plan->getTranslation('title', $locale)
                ?? $plan->getTranslation('title', $fallback)
                ?? (is_string($plan->title) ? $plan->title : '');

            $planDescription = $plan->getTranslation('description', $locale)
                ?? $plan->getTranslation('description', $fallback)
                ?? (is_string($plan->description) ? $plan->description : '');

            return [
                'id' => $plan->id,
                'title' => $planTitle,
                'description' => $planDescription,
                'slug' => $plan->slug,
                'poster' => $this->toAbsoluteUrl($plan->poster),
                'file' => $this->toAbsoluteUrl($plan->file),
            ];
        })->values()->all();

        $profileIds = array_values(array_unique(array_filter(
            array_map(
                static fn ($id): int => (int) $id,
                is_array($this->faculty_profile_instructor_ids) ? $this->faculty_profile_instructor_ids : [],
            ),
            static fn (int $id): bool => $id > 0,
        )));

        $facultyProfile = $this->instructors
            ->whereIn('id', $profileIds)
            ->sortBy(fn ($instructor) => array_search($instructor->id, $profileIds, true))
            ->values()
            ->map(function ($instructor) use ($locale, $fallback): array {
                $name = $instructor->getTranslation('name', $locale)
                    ?? $instructor->getTranslation('name', $fallback)
                    ?? (is_string($instructor->name) ? $instructor->name : '');

                $jobTitle = $instructor->getTranslation('title', $locale)
                    ?? $instructor->getTranslation('title', $fallback)
                    ?? (is_string($instructor->title) ? $instructor->title : '');

                return [
                    'id' => $instructor->id,
                    'name' => $name,
                    'title' => $jobTitle,
                    'role' => $jobTitle,
                    'photo' => $this->toAbsoluteUrl($instructor->photo),
                ];
            })
            ->all();

        return [
            'id' => $this->id,
            'slug' => (string) $this->id,
            'title' => $title,
            'description' => $description,
            'deanMessage' => is_string($deanMessage) ? $deanMessage : '',
            'vision' => is_string($vision) ? $vision : '',
            'mission' => is_string($mission) ? $mission : '',
            'cover' => $this->toAbsoluteUrl($this->cover),
            'departments' => $departments,
            'degreePrograms' => $degreeProgramsPayload,
            'faculty_profile' => $facultyProfile,
            'strategic_plans' => $strategicPlans,
        ];
    }
}
