<?php

namespace App\Filament\Resources\HomePageSections;

use App\Enums\HomePageSectionKey;
use App\Filament\Resources\HomePageSections\Pages\CreateAboutUsSection;
use App\Filament\Resources\HomePageSections\Pages\EditAboutUsSection;
use App\Filament\Resources\HomePageSections\Pages\ListAboutUsSections;
use App\Filament\Resources\HomePageSections\Schemas\AboutUsSectionForm;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class AboutUsSectionResource extends BaseHomePageSectionResource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Home Page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::InformationCircle;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'About Us';

    public static function sectionKey(): string
    {
        return HomePageSectionKey::AboutUs->value;
    }

    public static function form(Schema $schema): Schema
    {
        return AboutUsSectionForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAboutUsSections::route('/'),
            'create' => CreateAboutUsSection::route('/create'),
            'edit' => EditAboutUsSection::route('/{record}/edit'),
        ];
    }
}
