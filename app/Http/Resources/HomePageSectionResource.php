<?php

namespace App\Http\Resources;

use App\Enums\HomePageSectionKey;
use App\Support\MediaUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\HomePageSection */
class HomePageSectionResource extends JsonResource
{
    private function locale(): string
    {
        return request()->attributes->get('api_locale', config('app.fallback_locale', 'en'));
    }

    private function translated(string $field): string
    {
        $locale = $this->locale();
        $fallback = config('app.fallback_locale', 'en');

        return $this->getTranslation($field, $locale)
            ?? $this->getTranslation($field, $fallback)
            ?? (is_string($this->{$field}) ? $this->{$field} : '');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $sectionKey = $this->section_key instanceof HomePageSectionKey
            ? $this->section_key->value
            : (string) $this->section_key;

        return [
            'section_key' => $sectionKey,
            'heading' => $this->translated('heading'),
            'subheading' => $this->translated('subheading'),
            'description' => $this->translated('description'),
            'body_primary' => $this->translated('body_primary'),
            'body_secondary' => $this->translated('body_secondary'),
            'video_embed_url' => $this->video_embed_url,
            'image' => MediaUrl::toAbsolute($this->image),
            'image_one' => MediaUrl::toAbsolute($this->image_one),
            'image_two' => MediaUrl::toAbsolute($this->image_two),
            'image_three' => MediaUrl::toAbsolute($this->image_three),
            'image_four' => MediaUrl::toAbsolute($this->image_four),
            'stat_title' => $this->translated('stat_title'),
            'stat_one_label' => $this->translated('stat_one_label'),
            'stat_one_value' => $this->stat_one_value,
            'stat_two_label' => $this->translated('stat_two_label'),
            'stat_two_value' => $this->stat_two_value,
            'stat_three_label' => $this->translated('stat_three_label'),
            'stat_three_value' => $this->stat_three_value,
            'stat_four_label' => $this->translated('stat_four_label'),
            'stat_four_value' => $this->stat_four_value,
        ];
    }
}
