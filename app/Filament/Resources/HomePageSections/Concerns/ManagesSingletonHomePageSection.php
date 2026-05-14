<?php

namespace App\Filament\Resources\HomePageSections\Concerns;

use App\Models\HomePageSection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait ManagesSingletonHomePageSection
{
    abstract public static function sectionKey(): string;

    public static function getNavigationUrl(): string
    {
        $record = HomePageSection::query()->where('section_key', static::sectionKey())->first();

        if ($record !== null) {
            return static::getUrl('edit', ['record' => $record]);
        }

        return static::getUrl('create');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('section_key', static::sectionKey());
    }

    public static function canCreate(): bool
    {
        return ! HomePageSection::query()->where('section_key', static::sectionKey())->exists();
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
}
