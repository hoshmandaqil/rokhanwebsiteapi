<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Facility extends Model
{
    /** @use HasFactory<\Database\Factories\FacilityFactory> */
    use HasFactory;
    use HasTranslations;

    public $translatable = ['title', 'description'];

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
    ];

    protected static function booted(): void
    {
        static::saving(function (Facility $facility): void {
            if (blank($facility->slug) && filled($facility->title)) {
                $title = is_string($facility->title) ? $facility->title : ($facility->getTranslation('title', config('app.fallback_locale', 'en')) ?? '');
                $facility->slug = Str::slug($title);
            }
            if (filled($facility->slug)) {
                $facility->slug = static::ensureUniqueSlug($facility->slug, $facility->id);
            }
        });

        static::deleting(function (Facility $facility): void {
            if (filled($facility->image) && Storage::disk('local')->exists($facility->image)) {
                Storage::disk('local')->delete($facility->image);
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
}
