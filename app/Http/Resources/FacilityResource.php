<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin \App\Models\Facility */
class FacilityResource extends JsonResource
{
    private function locale(): string
    {
        return request()->attributes->get('api_locale', config('app.fallback_locale', 'en'));
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

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $title,
            'description' => $description,
            'image' => $this->image ? Storage::url($this->image) : null,
        ];
    }
}
