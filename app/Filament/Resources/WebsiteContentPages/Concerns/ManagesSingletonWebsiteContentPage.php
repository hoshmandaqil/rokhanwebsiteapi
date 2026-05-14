<?php

namespace App\Filament\Resources\WebsiteContentPages\Concerns;

use App\Enums\AboutType;
use App\Models\About;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait ManagesSingletonWebsiteContentPage
{
    abstract public static function pageKey(): string;

    public static function getNavigationUrl(): string
    {
        $record = About::query()->where('page_key', static::pageKey())->first();

        if ($record !== null) {
            return static::getUrl('edit', ['record' => $record]);
        }

        return static::getUrl('create');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('page_key', static::pageKey());
    }

    public static function canCreate(): bool
    {
        return ! About::query()->where('page_key', static::pageKey())->exists();
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canForceDelete(Model $record): bool
    {
        return false;
    }

    public static function canForceDeleteAny(): bool
    {
        return false;
    }

    /**
     * @return array<string, string>
     */
    public static function defaultTitleForLocales(): array
    {
        $label = static::getNavigationLabel() ?? 'Page';
        $locales = ['en', 'ps', 'prs'];

        return array_combine($locales, array_map(static fn () => $label, $locales)) ?: [];
    }

    protected static function defaultType(): AboutType
    {
        return AboutType::University;
    }
}
