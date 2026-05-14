<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

/**
 * Picks Spatie-translatable field values for public API responses.
 *
 * When ?locale= is not the app fallback (typically "en"), only that locale is
 * returned — never another language as a substitute.
 *
 * For the fallback locale, behaviour matches the previous API: requested
 * locale, then fallback key, then any non-empty translation, then a raw
 * string attribute when present.
 */
final class ApiLocaleTranslation
{
    public static function apiLocale(): string
    {
        $loc = request()->attributes->get('api_locale');

        return is_string($loc) && $loc !== '' ? $loc : (string) config('app.fallback_locale', 'en');
    }

    public static function appFallbackLocale(): string
    {
        return (string) config('app.fallback_locale', 'en');
    }

    public static function isNonFallbackRequest(): bool
    {
        return self::apiLocale() !== self::appFallbackLocale();
    }

    /**
     * @param  array<string, mixed>  $translations
     */
    private static function nonEmptyString(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }
        $text = trim(str_replace("\xc2\xa0", ' ', strip_tags($value)));

        return $text !== '' ? $value : null;
    }

    /**
     * @param  array<string, mixed>  $translations
     */
    private static function pickLooseFromTranslations(array $translations, string $locale, string $fallback): ?string
    {
        foreach ([$locale, $fallback] as $loc) {
            if (! is_string($loc) || $loc === '' || ! array_key_exists($loc, $translations)) {
                continue;
            }
            $picked = self::nonEmptyString($translations[$loc] ?? null);
            if ($picked !== null) {
                return $picked;
            }
        }

        foreach ($translations as $value) {
            $picked = self::nonEmptyString($value);
            if ($picked !== null) {
                return $picked;
            }
        }

        return null;
    }

    /**
     * @param  Model  $model  Must use {@see \Spatie\Translatable\HasTranslations}
     */
    public static function pick(Model $model, string $field): string
    {
        if (! method_exists($model, 'getTranslations')) {
            return '';
        }

        /** @var array<string, mixed> $translations */
        $translations = $model->getTranslations($field);
        $locale = self::apiLocale();
        $fallback = self::appFallbackLocale();

        if (self::isNonFallbackRequest()) {
            if (! array_key_exists($locale, $translations)) {
                return '';
            }
            $v = $translations[$locale];
            if (! is_string($v)) {
                return '';
            }
            $picked = self::nonEmptyString($v);

            return $picked !== null ? $picked : '';
        }

        $picked = self::pickLooseFromTranslations($translations, $locale, $fallback);
        if ($picked !== null) {
            return $picked;
        }

        $attr = $model->getAttribute($field);

        return is_string($attr) ? $attr : '';
    }
}
