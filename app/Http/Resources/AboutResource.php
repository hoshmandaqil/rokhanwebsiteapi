<?php

namespace App\Http\Resources;

use App\Enums\AboutType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/** @mixin \App\Models\About */
class AboutResource extends JsonResource
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

    private function translated(string $field): string
    {
        $locale = $this->locale();
        $fallback = config('app.fallback_locale', 'en');
        $raw = $this->getTranslation($field, $locale)
            ?? $this->getTranslation($field, $fallback)
            ?? '';

        return is_string($raw) ? $raw : '';
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $title = trim(strip_tags($this->translated('title')));
        $content = $this->translated('description');
        $plainDescription = trim(strip_tags($content));
        $type = $this->type instanceof AboutType ? $this->type->value : (string) $this->type;
        $slug = Str::slug($title);

        return [
            'id' => $this->id,
            'slug' => $slug,
            'type' => $type,
            'title' => $title,
            'description' => $plainDescription,
            'content' => $content,
            'image' => $this->toAbsoluteUrl($this->image),
            'link' => '/about/'.$slug,
        ];
    }
}
