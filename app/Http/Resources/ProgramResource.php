<?php

namespace App\Http\Resources;

use App\Enums\ProgramLevel;
use App\Support\ApiLocaleTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Throwable;

/** @mixin \App\Models\Program */
class ProgramResource extends JsonResource
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

    private function translateHtml(string $field): string
    {
        return ApiLocaleTranslation::pick($this->resource, $field);
    }

    private function translateDegree(): string
    {
        return ApiLocaleTranslation::pick($this->resource, 'degree');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $level = $this->level instanceof ProgramLevel
            ? $this->level
            : ProgramLevel::from((string) $this->level);

        $path = $level->pathSegment();
        $slug = $this->slug;

        $title = trim(strip_tags(ApiLocaleTranslation::pick($this->resource, 'title')));

        $descriptionHtml = $this->translateHtml('description');
        $descriptionPlain = trim(strip_tags($descriptionHtml));
        $degree = $this->translateDegree();

        $departmentTitle = '';
        if ($this->relationLoaded('department') && $this->department) {
            $departmentTitle = ApiLocaleTranslation::pick($this->department, 'title');
        }

        $cover = $this->toAbsoluteUrl($this->cover);
        $thumb = $this->toAbsoluteUrl($this->thumbnail) ?? $cover;
        $imageUrl = $thumb ?? $cover ?? '';

        $highlights = is_array($this->highlights) ? $this->highlights : [];

        $visionHtml = $this->translateHtml('vision_content');
        $missionHtml = $this->translateHtml('mission_content');
        $values = is_array($this->program_values)
            ? array_values(array_filter($this->program_values, fn ($v) => is_string($v) && $v !== ''))
            : [];

        $visionMission = ($visionHtml !== '' || $missionHtml !== '' || $values !== [])
            ? [
                'vision' => $visionHtml,
                'mission' => $missionHtml,
                'values' => $values,
            ]
            : null;

        $academicStructureHtml = $this->translateHtml('academic_structure_html');
        $feeStructureHtml = $this->translateHtml('fee_structure_html');

        return [
            'id' => $this->id,
            'slug' => $slug,
            'level' => $level->value,
            'title' => $title,
            'description' => $descriptionPlain,
            'descriptionHtml' => $descriptionHtml,
            'duration' => $this->duration ?? '',
            'degree' => $degree,
            'imageUrl' => $imageUrl,
            'cover' => $cover,
            'thumbnail' => $thumb,
            'href' => '/programs/'.$path.'/'.$slug,
            'highlights' => $highlights,
            'visionMission' => $visionMission,
            'academicStructureHtml' => $academicStructureHtml !== '' ? $academicStructureHtml : null,
            'feeStructureHtml' => $feeStructureHtml !== '' ? $feeStructureHtml : null,
            'department' => $departmentTitle,
            'sortOrder' => (int) $this->sort_order,
            'isPublished' => (bool) $this->is_published,
        ];
    }
}
