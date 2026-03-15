<?php

namespace App\Models;

use App\Enums\PlanScope;
use App\Enums\PlanType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Plan extends Model
{
    /** @use HasFactory<\Database\Factories\PlanFactory> */
    use HasFactory;
    use HasTranslations;

    public $translatable = ['title', 'description'];

    protected $fillable = [
        'type',
        'scope',
        'faculty_id',
        'department_id',
        'title',
        'description',
        'slug',
        'poster',
        'file',
    ];

    protected static function booted(): void
    {
        static::saving(function (Plan $plan): void {
            if (blank($plan->slug) && filled($plan->title)) {
                $title = is_string($plan->title) ? $plan->title : ($plan->getTranslation('title', config('app.fallback_locale', 'en')) ?? '');
                $plan->slug = Str::slug($title);
            }
            if (filled($plan->slug)) {
                $plan->slug = static::ensureUniqueSlug($plan->slug, $plan->id);
            }
        });

        static::deleting(function (Plan $plan): void {
            if (filled($plan->poster) && Storage::disk('local')->exists($plan->poster)) {
                Storage::disk('local')->delete($plan->poster);
            }
            if (filled($plan->file) && Storage::disk('local')->exists($plan->file)) {
                Storage::disk('local')->delete($plan->file);
            }
        });
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
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => PlanType::class,
            'scope' => PlanScope::class,
        ];
    }

    /**
     * @return BelongsTo<Faculty, $this>
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
