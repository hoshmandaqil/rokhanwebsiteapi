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

        $programs = $this->departments->map(function ($department) use ($locale, $fallback): array {
            $title = $department->getTranslation('title', $locale)
                ?? $department->getTranslation('title', $fallback)
                ?? (is_string($department->title) ? $department->title : '');

            $programDescription = $department->getTranslation('description', $locale)
                ?? $department->getTranslation('description', $fallback)
                ?? (is_string($department->description) ? $department->description : '');

            return [
                'id' => $department->id,
                'title' => $title,
                'description' => $programDescription,
                'cover' => $this->toAbsoluteUrl($department->cover),
                'faculty_id' => $department->faculty_id,
                'created_at' => $department->created_at?->toIso8601String(),
                'updated_at' => $department->updated_at?->toIso8601String(),
            ];
        })->values()->all();

        $strategicPlans = $this->strategicPlans->map(function ($plan) use ($locale, $fallback): array {
            $title = $plan->getTranslation('title', $locale)
                ?? $plan->getTranslation('title', $fallback)
                ?? (is_string($plan->title) ? $plan->title : '');

            $planDescription = $plan->getTranslation('description', $locale)
                ?? $plan->getTranslation('description', $fallback)
                ?? (is_string($plan->description) ? $plan->description : '');

            return [
                'id' => $plan->id,
                'title' => $title,
                'description' => $planDescription,
                'slug' => $plan->slug,
                'poster' => $this->toAbsoluteUrl($plan->poster),
                'file' => $this->toAbsoluteUrl($plan->file),
            ];
        })->values()->all();

        return [
            'id' => $this->id,
            'title' => $title,
            'description' => $description,
            'overview' => $description,
            'cover' => $this->toAbsoluteUrl($this->cover),
            'programs' => $programs,
            'strategic_plans' => $strategicPlans,
        ];
    }
}
