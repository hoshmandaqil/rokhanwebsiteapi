<?php

namespace App\Models;

use App\Enums\HomePageSectionKey;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HomePageSection extends Model
{
    use HasTranslations;

    public $translatable = [
        'heading',
        'subheading',
        'description',
        'body_primary',
        'body_secondary',
        'stat_title',
        'stat_one_label',
        'stat_two_label',
        'stat_three_label',
        'stat_four_label',
    ];

    protected $fillable = [
        'section_key',
        'heading',
        'subheading',
        'description',
        'body_primary',
        'body_secondary',
        'video_embed_url',
        'image',
        'image_one',
        'image_two',
        'image_three',
        'image_four',
        'stat_title',
        'stat_one_label',
        'stat_one_value',
        'stat_two_label',
        'stat_two_value',
        'stat_three_label',
        'stat_three_value',
        'stat_four_label',
        'stat_four_value',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'section_key' => HomePageSectionKey::class,
        ];
    }
}
