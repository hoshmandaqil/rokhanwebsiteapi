<?php

namespace App\Http\Resources;

use App\Support\ApiLocaleTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Throwable;

/** @mixin \App\Models\Department */
class DepartmentResource extends JsonResource
{
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
        $title = trim(strip_tags(ApiLocaleTranslation::pick($this->resource, 'title')));
        $description = ApiLocaleTranslation::pick($this->resource, 'description');
        $vision = ApiLocaleTranslation::pick($this->resource, 'vision_content');
        $mission = ApiLocaleTranslation::pick($this->resource, 'mission_content');
        $slug = (string) ($this->slug ?: $this->id);
        $cover = $this->toAbsoluteUrl($this->cover);

        $faculty = null;
        if ($this->relationLoaded('faculty') && $this->faculty) {
            $facultyTitle = trim(strip_tags(ApiLocaleTranslation::pick($this->faculty, 'title')));
            $faculty = [
                'id' => $this->faculty->id,
                'slug' => (string) $this->faculty->id,
                'title' => $facultyTitle,
                'href' => '/faculties/'.$this->faculty->id,
                'cover' => $this->toAbsoluteUrl($this->faculty->cover),
            ];
        }

        $programs = [];
        if ($this->relationLoaded('programs')) {
            $programs = $this->programs
                ->filter(fn ($program) => (bool) $program->is_published)
                ->sort(function ($a, $b): int {
                    $order = ($a->sort_order ?? 0) <=> ($b->sort_order ?? 0);

                    return $order !== 0 ? $order : $a->id <=> $b->id;
                })
                ->values()
                ->map(fn ($program) => (new ProgramResource($program))->toArray($request))
                ->all();
        }

        return [
            'id' => $this->id,
            'slug' => $slug,
            'title' => $title,
            'description' => $description,
            'vision' => is_string($vision) ? $vision : '',
            'mission' => is_string($mission) ? $mission : '',
            'cover' => $cover,
            'imageUrl' => $cover ?? '',
            'href' => '/departments/'.$slug,
            'faculty_id' => $this->faculty_id,
            'faculty' => $faculty,
            'programs' => $programs,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
