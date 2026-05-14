<?php

namespace App\Models;

use App\Enums\LeadershipMessageType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class LeadershipMessage extends Model
{
    use HasTranslations;

    public $translatable = ['name', 'title', 'message'];

    protected $fillable = [
        'type',
        'department_id',
        'faculty_id',
        'name',
        'title',
        'message',
        'image',
        'cover',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => LeadershipMessageType::class,
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

    public function scopeChairman(Builder $query): Builder
    {
        return $query->where('type', LeadershipMessageType::Chairman);
    }

    public function scopeVcAcademic(Builder $query): Builder
    {
        return $query->where('type', LeadershipMessageType::VcAcademic);
    }

    public function scopeVcAdmin(Builder $query): Builder
    {
        return $query->where('type', LeadershipMessageType::VcAdmin);
    }

    public function scopeForDepartment(Builder $query, int $departmentId): Builder
    {
        return $query->where('type', LeadershipMessageType::Department)
            ->where('department_id', $departmentId);
    }

    public function scopeForFaculty(Builder $query, int $facultyId): Builder
    {
        return $query->where('type', LeadershipMessageType::Faculty)
            ->where('faculty_id', $facultyId);
    }
}
