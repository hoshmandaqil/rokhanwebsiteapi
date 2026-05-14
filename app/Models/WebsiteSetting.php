<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_tagline',
        'logo',
        'favicon',
        'contact_email',
        'contact_phone',
        'contact_phone_secondary',
        'contact_whatsapp_url',
        'office_hours',
        'address_line_1',
        'address_line_2',
        'city',
        'region',
        'postal_code',
        'country',
        'social_facebook_url',
        'social_instagram_url',
        'social_x_url',
        'social_youtube_url',
        'social_linkedin_url',
        'social_tiktok_url',
        'meta_default_description',
    ];

    public static function singleton(): self
    {
        return self::query()->firstOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Rokhan University',
                'site_tagline' => 'Building futures through education. A leading institution dedicated to academic excellence, innovation, and service to society.',
            ],
        );
    }
}
