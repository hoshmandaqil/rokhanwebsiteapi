<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Career */
class CareerResource extends JsonResource
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
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'created_at' => $this->created_at?->toIso8601String(),
            'title' => trim(strip_tags($this->translated('title'))),
            'department' => trim(strip_tags($this->translated('department'))),
            'location' => trim(strip_tags($this->translated('location'))),
            'type' => trim(strip_tags($this->translated('employment_type'))),
            'deadline' => $this->deadline?->toDateString(),
            'description' => $this->translated('description'),
            'responsibilities' => $this->translated('responsibilities'),
            'qualifications' => $this->translated('qualifications'),
            'submission_guidelines' => $this->translated('submission_guidelines'),
            'is_published' => $this->is_published,
            'link' => '/career/'.$this->slug,
        ];
    }
}
