<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteBanner extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'image',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }
}
