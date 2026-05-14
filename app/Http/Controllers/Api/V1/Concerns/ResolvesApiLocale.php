<?php

namespace App\Http\Controllers\Api\V1\Concerns;

use Illuminate\Http\Request;

trait ResolvesApiLocale
{
    /**
     * Resolve the API locale from query param or Accept-Language header.
     * Unknown values are clamped to {@see config('app.available_locales')} so
     * clients never receive a mismatched translation strategy (e.g. Accept-Language "de"
     * must not imply a strict locale that strips all content).
     */
    private function resolveLocale(Request $request): string
    {
        $candidate = null;

        $localeQuery = $request->query('locale');
        if (filled($localeQuery) && is_string($localeQuery)) {
            $candidate = $localeQuery;
        } else {
            $acceptLanguage = $request->header('Accept-Language');
            if (filled($acceptLanguage) && preg_match('/^([a-z]{2}(?:-[A-Z]{2})?)/i', $acceptLanguage, $matches)) {
                $candidate = strtolower(str_replace('-', '_', $matches[1]));
            }
        }

        if ($candidate === null || $candidate === '') {
            $candidate = (string) config('app.fallback_locale', 'en');
        }

        $allowed = config('app.available_locales', ['en', 'ps', 'prs']);
        if (! is_array($allowed) || $allowed === []) {
            $allowed = ['en', 'ps', 'prs'];
        }

        $normalized = in_array($candidate, $allowed, true)
            ? $candidate
            : (string) config('app.fallback_locale', 'en');

        if (! in_array($normalized, $allowed, true)) {
            $normalized = $allowed[0];
        }

        $request->attributes->set('api_locale', $normalized);

        return $normalized;
    }
}
