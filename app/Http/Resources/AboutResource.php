<?php

namespace App\Http\Resources;

use App\Enums\AboutPageKey;
use App\Enums\AboutType;
use App\Support\ApiLocaleTranslation;
use App\Support\MediaUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/** @mixin \App\Models\About */
class AboutResource extends JsonResource
{
    /**
     * @return list<array{id: int, title: string, slug: string|null, file: string|null}>
     */
    private function strategicPlanDocuments(): array
    {
        $documents = is_array($this->documents) ? $this->documents : [];

        return collect($documents)
            ->values()
            ->map(function ($row, int $index): ?array {
                if (! is_array($row)) {
                    return null;
                }

                $title = trim((string) ($row['title'] ?? ''));
                $file = $row['file'] ?? null;
                if (is_array($file)) {
                    $file = collect($file)->filter()->first();
                }
                $file = is_string($file) ? trim($file) : null;

                if ($title === '') {
                    return null;
                }

                return [
                    'id' => $index + 1,
                    'title' => $title,
                    'slug' => Str::slug($title) ?: null,
                    'file' => MediaUrl::toAbsolute($file),
                ];
            })
            ->filter()
            ->values()
            ->all();
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

        $payload = [
            'id' => $this->id,
            'slug' => $slug,
            'page_key' => $pageKey,
            'type' => $type,
            'title' => $title,
            'description' => $plainDescription,
            'content' => $content,
            'image' => MediaUrl::toAbsolute($this->image),
            'link' => '/about/'.$slug,
        ];

        if ($pageKey === AboutPageKey::StrategicPlan->value) {
            $payload['documents'] = $this->strategicPlanDocuments();
        }

        return $payload;
    }
}
