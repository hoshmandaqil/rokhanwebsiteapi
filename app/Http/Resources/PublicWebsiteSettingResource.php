<?php

namespace App\Http\Resources;

use App\Support\MediaUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\WebsiteSetting */
class PublicWebsiteSettingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'site_name' => $this->site_name,
            'site_tagline' => $this->site_tagline,
            'logo_url' => MediaUrl::toAbsolute($this->logo),
            'favicon_url' => MediaUrl::toAbsolute($this->favicon),
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'contact_phone_secondary' => $this->contact_phone_secondary,
            'contact_whatsapp_url' => $this->contact_whatsapp_url,
            'office_hours' => $this->office_hours,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'city' => $this->city,
            'region' => $this->region,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'social_facebook_url' => $this->social_facebook_url,
            'social_instagram_url' => $this->social_instagram_url,
            'social_x_url' => $this->social_x_url,
            'social_youtube_url' => $this->social_youtube_url,
            'social_linkedin_url' => $this->social_linkedin_url,
            'social_tiktok_url' => $this->social_tiktok_url,
            'meta_default_description' => $this->meta_default_description,
        ];
    }
}
