<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Career extends Model
{
    /** @use HasFactory<\Database\Factories\CareerFactory> */
    use HasFactory;

    use HasTranslations;

    public $translatable = [
        'title',
        'department',
        'location',
        'employment_type',
        'description',
        'responsibilities',
        'qualifications',
        'submission_guidelines',
    ];

    protected $fillable = [
        'title',
        'slug',
        'department',
        'location',
        'employment_type',
        'deadline',
        'description',
        'responsibilities',
        'qualifications',
        'submission_guidelines',
        'is_published',
    ];

    protected static function booted(): void
    {
        static::saving(function (Career $career): void {
            if (blank($career->slug) && filled($career->title)) {
                $title = is_string($career->title) ? $career->title : ($career->getTranslation('title', config('app.fallback_locale', 'en')) ?? '');
                $career->slug = Str::slug($title);
            }

            if (filled($career->slug)) {
                $career->slug = static::ensureUniqueSlug($career->slug, $career->id);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'is_published' => 'boolean',
        ];
    }

    public static function ensureUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug;
        $count = 1;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId !== null, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = $base.'-'.(++$count);
        }

        return $slug;
    }
}
