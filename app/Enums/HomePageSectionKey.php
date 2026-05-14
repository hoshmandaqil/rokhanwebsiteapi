<?php

namespace App\Enums;

enum HomePageSectionKey: string
{
    case TopBanner = 'top-banner';
    case Video = 'video';
    case AboutUs = 'about-us';

    public function label(): string
    {
        return match ($this) {
            self::TopBanner => 'Top Banner',
            self::Video => 'Video',
            self::AboutUs => 'About Us',
        };
    }
}
