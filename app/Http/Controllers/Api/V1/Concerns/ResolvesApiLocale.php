<?php

namespace App\Http\Controllers\Api\V1\Concerns;

use Illuminate\Http\Request;

trait ResolvesApiLocale
{
    /**
     * Resolve the API locale from query param or Accept-Language header.
     */
    private function resolveLocale(Request $request): string
    {
        $locale = $request->query('locale');

        if (filled($locale)) {
            $request->attributes->set('api_locale', $locale);

            return $locale;
        }

        $acceptLanguage = $request->header('Accept-Language');
        if (filled($acceptLanguage) && preg_match('/^([a-z]{2}(?:-[A-Z]{2})?)/i', $acceptLanguage, $matches)) {
            $locale = str_replace('-', '_', $matches[1]);
            $request->attributes->set('api_locale', $locale);

            return $locale;
        }

        $locale = config('app.fallback_locale', 'en');
        $request->attributes->set('api_locale', $locale);

        return $locale;
    }
}
