<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Department extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'description', 'vision_content', 'mission_content'];

    protected $fillable = [
        'slug',
        'title',
        'description',
        'vision_content',
        'mission_content',
        'cover',
        'faculty_id',
    ];

    protected static function booted(): void
    {
        static::saving(function (Department $department): void {
            if (blank($department->slug) && filled($department->title)) {
                $title = is_string($department->title)
                    ? $department->title
                    : ($department->getTranslation('title', config('app.fallback_locale', 'en')) ?? '');
                $department->slug = Str::slug(strip_tags((string) $title));
            }

            if (blank($department->slug)) {
                $department->slug = 'department';
            }

            if (filled($department->slug)) {
                $department->slug = static::ensureUniqueSlug($department->slug, $department->id);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

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
     * @return BelongsTo<Faculty, $this>
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * @return HasMany<LeadershipMessage, $this>
     */
    public function leadershipMessages(): HasMany
    {
        return $this->hasMany(LeadershipMessage::class);
    }

    /**
     * @return HasMany<Plan, $this>
     */
    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    /**
     * @return HasMany<Program, $this>
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }
}
