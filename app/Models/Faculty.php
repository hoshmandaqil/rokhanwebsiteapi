<?php

namespace App\Models;

use App\Enums\PlanScope;
use App\Enums\PlanType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Faculty extends Model
{
    /** @use HasFactory<\Database\Factories\FacultyFactory> */
    use HasFactory;

    use HasTranslations;

    public $translatable = [
        'title',
        'description',
        'dean_message',
        'mission_content',
        'vision_content',
    ];

    protected $fillable = [
        'title',
        'description',
        'cover',
        'dean_message',
        'mission_content',
        'vision_content',
        'faculty_profile_instructor_ids',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'faculty_profile_instructor_ids' => 'array',
        ];
    }

    /**
     * @return HasMany<LeadershipMessage, $this>
     */
    public function leadershipMessages(): HasMany
    {
        return $this->hasMany(LeadershipMessage::class);
    }

    /**
     * @return HasMany<Department, $this>
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    /**
     * @return HasMany<Plan, $this>
     */
    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    /**
     * @return HasMany<Plan, $this>
     */
    public function strategicPlans(): HasMany
    {
        return $this->hasMany(Plan::class)
            ->where('scope', PlanScope::Faculty)
            ->where('type', PlanType::StrategicPlan);
    }

    /**
     * @return HasMany<Instructor, $this>
     */
    public function instructors(): HasMany
    {
        return $this->hasMany(Instructor::class);
    }
}
