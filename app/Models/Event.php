<?php

namespace App\Models;

use App\Enums\EventType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    use HasTranslations;

    public $translatable = ['title', 'description', 'location'];

    protected $fillable = [
        'type',
        'title',
        'slug',
        'description',
        'location',
        'start_at',
        'end_at',
        'cover',
        'thumbnail',
        'is_published',
    ];

    protected static function booted(): void
    {
        static::saving(function (Event $event): void {
            if (blank($event->slug) && filled($event->title)) {
                $title = is_string($event->title) ? $event->title : ($event->getTranslation('title', config('app.fallback_locale', 'en')) ?? '');
                $event->slug = Str::slug($title);
            }
            if (filled($event->slug)) {
                $event->slug = static::ensureUniqueSlug($event->slug, $event->id);
            }
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => EventType::class,
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
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
}
