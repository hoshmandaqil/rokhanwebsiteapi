<?php

namespace App\Http\Resources;

use App\Support\ApiLocaleTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Throwable;

/** @mixin \App\Models\Event */
class EventResource extends JsonResource
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
        $rawTitle = ApiLocaleTranslation::pick($this->resource, 'title');
        $rawDescription = ApiLocaleTranslation::pick($this->resource, 'description');
        $rawLocation = ApiLocaleTranslation::pick($this->resource, 'location');
        if ($rawLocation === '') {
            $rawLocation = null;
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'type' => $this->type?->value ?? '',
            'title' => $rawTitle,
            'description' => trim(strip_tags($rawDescription)),
            'content' => $rawDescription,
            'location' => $rawLocation,
            'start_at' => $this->start_at?->toIso8601String(),
            'end_at' => $this->end_at?->toIso8601String(),
            'img' => $this->toAbsoluteUrl($this->cover) ?? '',
            'cover' => $this->toAbsoluteUrl($this->cover),
            'thumbnail' => $this->toAbsoluteUrl($this->thumbnail),
            'link' => '/events/'.$this->slug,
        ];
    }
}
