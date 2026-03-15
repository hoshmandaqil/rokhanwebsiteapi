<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin \App\Models\News */
class NewsResource extends JsonResource
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

        $rawTitle = $this->getTranslation('title', $locale)
            ?? $this->getTranslation('title', $fallback)
            ?? (is_string($this->title) ? $this->title : '');

        $rawDescription = $this->getTranslation('description', $locale)
            ?? $this->getTranslation('description', $fallback)
            ?? (is_string($this->description) ? $this->description : '');

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $rawTitle,
            'description' => trim(strip_tags($rawDescription)),
            'content' => $rawDescription,
            'date' => $this->date?->format('F j, Y') ?? '',
            'img' => $this->cover
                ? Storage::url($this->cover)
                : ($this->thumbnail ? Storage::url($this->thumbnail) : ''),
            'thumbnail' => $this->thumbnail ? Storage::url($this->thumbnail) : null,
            'link' => '/news/'.$this->slug,
        ];
    }
}
