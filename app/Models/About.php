<?php

namespace App\Models;

use App\Enums\AboutType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class About extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'description'];

    protected $fillable = [
        'type',
        'department_id',
        'faculty_id',
        'title',
        'description',
        'image',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => AboutType::class,
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
