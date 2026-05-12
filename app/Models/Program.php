<?php

namespace App\Models;

use App\Enums\ProgramLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Program extends Model
{
    use HasTranslations;

    public $translatable = [
        'title',
        'description',
        'degree',
        'vision_content',
        'mission_content',
        'academic_structure_html',
        'fee_structure_html',
    ];

    protected $fillable = [
        'department_id',
        'level',
        'slug',
        'title',
        'description',
        'duration',
        'degree',
        'cover',
        'thumbnail',
        'highlights',
        'program_values',
        'vision_content',
        'mission_content',
        'academic_structure_html',
        'fee_structure_html',
        'sort_order',
        'is_published',
    ];

    protected static function booted(): void
    {
        static::saving(function (Program $program): void {
            if (blank($program->slug) && filled($program->title)) {
                $title = is_string($program->title)
                    ? $program->title
                    : ($program->getTranslation('title', config('app.fallback_locale', 'en')) ?? '');
                $program->slug = Str::slug($title);
            }
            if (filled($program->slug)) {
                $program->slug = static::ensureUniqueSlug($program->slug, $program->id);
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
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level' => ProgramLevel::class,
            'highlights' => 'array',
            'program_values' => 'array',
            'is_published' => 'boolean',
        ];
    }
}
