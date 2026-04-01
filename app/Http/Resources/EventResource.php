<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Throwable;

/** @mixin \App\Models\Event */
class EventResource extends JsonResource
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

        $rawTitle = $this->getTranslation('title', $locale)
            ?? $this->getTranslation('title', $fallback)
            ?? (is_string($this->title) ? $this->title : '');

        $rawDescription = $this->getTranslation('description', $locale)
            ?? $this->getTranslation('description', $fallback)
            ?? (is_string($this->description) ? $this->description : '');

        $rawLocation = $this->getTranslation('location', $locale)
            ?? $this->getTranslation('location', $fallback)
            ?? (is_string($this->location) ? $this->location : null);

        return [
            'id' => $this->id,
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
            'link' => '/events/'.$this->id,
        ];
    }
}
