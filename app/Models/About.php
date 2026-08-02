<?php

namespace App\Models;

use App\Enums\AboutType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class About extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'description'];

    protected $fillable = [
        'type',
        'page_key',
        'department_id',
        'faculty_id',
        'title',
        'description',
        'image',
        'documents',
    ];

    protected static function booted(): void
    {
        static::updating(function (About $about): void {
            $original = $about->getOriginal('documents');
            $originalFiles = self::documentFilePaths(is_array($original) ? $original : []);
            $nextFiles = self::documentFilePaths(is_array($about->documents) ? $about->documents : []);

            foreach (array_diff($originalFiles, $nextFiles) as $path) {
                if (Storage::disk('local')->exists($path)) {
                    Storage::disk('local')->delete($path);
                }
            }
        });

        static::deleting(function (About $about): void {
            foreach (self::documentFilePaths(is_array($about->documents) ? $about->documents : []) as $path) {
                if (Storage::disk('local')->exists($path)) {
                    Storage::disk('local')->delete($path);
                }
            }
        });
    }

    /**
     * @param  list<array<string, mixed>>  $documents
     * @return list<string>
     */
    private static function documentFilePaths(array $documents): array
    {
        return collect($documents)
            ->map(function ($row): ?string {
                if (! is_array($row)) {
                    return null;
                }
                $file = $row['file'] ?? null;
                if (is_array($file)) {
                    $file = collect($file)->filter()->first();
                }

                return is_string($file) && filled($file) ? $file : null;
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => AboutType::class,
            'documents' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return BelongsTo<Faculty, $this>
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function scopeUniversity(Builder $query): Builder
    {
        return $query->where('type', AboutType::University);
    }

    public function scopeHistory(Builder $query): Builder
    {
        return $query->where('type', AboutType::History);
    }

    public function scopeForDepartment(Builder $query, int $departmentId): Builder
    {
        return $query->where('type', AboutType::Department)
            ->where('department_id', $departmentId);
    }

    public function scopeForProgram(Builder $query): Builder
    {
        return $query->where('type', AboutType::Program);
    }
}
