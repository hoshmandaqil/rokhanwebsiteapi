<?php

namespace App\Filament\Concerns;

use App\Models\About;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

trait ResolvesAboutFilamentRecordTitle
{
    /**
     * Spatie stores `title` as JSON; getAttribute('title') returns an array and breaks Filament.
     */
    public static function getRecordTitle(?Model $record): string|Htmlable|null
    {
        if (! $record instanceof About) {
            return static::getModelLabel();
        }

        $locale = app()->getLocale();
        $fallback = config('app.fallback_locale', 'en');
        $title = $record->getTranslation('title', $locale)
            ?: $record->getTranslation('title', $fallback);

        if (is_string($title) && trim(strip_tags($title)) !== '') {
            return trim(strip_tags($title));
        }

        return static::getNavigationLabel() ?? static::getModelLabel();
    }
}
