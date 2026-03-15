<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Activity extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityFactory> */
    use HasFactory;

    use HasTranslations;

    public $translatable = ['title', 'description'];

    protected $fillable = [
        'title',
        'slug',
        'description',
        'date',
        'cover',
        'thumbnail',
    ];

    protected static function booted(): void
    {
        static::saving(function (Activity $activity): void {
            if (blank($activity->slug) && filled($activity->title)) {
                $title = is_string($activity->title) ? $activity->title : ($activity->getTranslation('title', config('app.fallback_locale', 'en')) ?? '');
                $activity->slug = Str::slug($title);
            }
            if (filled($activity->slug)) {
                $activity->slug = static::ensureUniqueSlug($activity->slug, $activity->id);
            }
        });
    }

    /**
     * Ensure the slug is unique by appending a numeric suffix if needed.
     */
    public static function ensureUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug;
        $count = 1;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId !== null, fn ($q) => $q->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = $base.'-'.(++$count);
        }

        return $slug;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
