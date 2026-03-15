<?php

namespace App\Models;

use App\Enums\AboutType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Vision extends Model
{
    use HasTranslations;

    public $translatable = ['content'];

    protected $fillable = ['type', 'faculty_id', 'department_id', 'content'];

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
