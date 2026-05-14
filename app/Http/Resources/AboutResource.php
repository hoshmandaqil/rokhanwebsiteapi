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

    /**
     * Prefer API locale, then app fallback, then any other locale with non-empty text.
     * Spatie often returns '' for missing locales; null-coalescing alone skips real content in other languages.
     */
    private function translated(string $field): string
    {
        $locale = $this->locale();
        $fallback = config('app.fallback_locale', 'en');

        /** @var array<string, mixed> $translations */
        $translations = $this->getTranslations($field);

        $nonEmpty = function (mixed $value): ?string {
            if (! is_string($value)) {
                return null;
            }
            $text = trim(str_replace("\xc2\xa0", ' ', strip_tags($value)));

            return $text !== '' ? $value : null;
        };

        foreach ([$locale, $fallback] as $loc) {
            if (! is_string($loc) || $loc === '') {
                continue;
            }
            if (array_key_exists($loc, $translations)) {
                $picked = $nonEmpty($translations[$loc]);
                if ($picked !== null) {
                    return $picked;
                }
            }
        }

        foreach ($translations as $value) {
            $picked = $nonEmpty($value);
            if ($picked !== null) {
                return $picked;
            }
        }

        return '';
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
        $pageKey = filled($this->page_key) ? (string) $this->page_key : null;
        $slug = $pageKey ?? Str::slug($title);

        return [
            'id' => $this->id,
            'slug' => $slug,
            'page_key' => $pageKey,
            'type' => $type,
            'title' => $title,
            'description' => $plainDescription,
            'content' => $content,
            'image' => $this->toAbsoluteUrl($this->image),
            'link' => '/about/'.$slug,
        ];
    }
}
