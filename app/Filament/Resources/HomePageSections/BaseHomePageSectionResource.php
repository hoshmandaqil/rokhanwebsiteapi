<?php

namespace App\Filament\Resources\HomePageSections;

use App\Filament\Resources\HomePageSections\Concerns\ManagesSingletonHomePageSection;
use App\Filament\Resources\HomePageSections\Tables\HomePageSectionTable;
use App\Models\HomePageSection;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

abstract class BaseHomePageSectionResource extends Resource
{
    use ManagesSingletonHomePageSection;
    use Translatable;

    protected static ?string $model = HomePageSection::class;

    public static function table(Table $table): Table
    {
        return HomePageSectionTable::configure($table);
    }

    public static function getModelLabel(): string
    {
        return static::getNavigationLabel() ?? 'Section';
    }

    public static function getPluralModelLabel(): string
    {
        return static::getNavigationLabel() ?? 'Sections';
    }
}
