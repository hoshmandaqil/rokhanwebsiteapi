<?php

namespace App\Http\Resources;

use App\Enums\AboutType;
use App\Support\ApiLocaleTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/** @mixin \App\Models\About */
class AboutResource extends JsonResource
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
        $title = trim((string) preg_replace('/\s+page$/iu', '', $title));
        $content = ApiLocaleTranslation::pick($this->resource, 'description');
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
