<?php

namespace App\Filament\Resources\HomePageSections;

use App\Enums\HomePageSectionKey;
use App\Filament\Resources\HomePageSections\Pages\CreateTopBannerSection;
use App\Filament\Resources\HomePageSections\Pages\EditTopBannerSection;
use App\Filament\Resources\HomePageSections\Pages\ListTopBannerSections;
use App\Filament\Resources\HomePageSections\Schemas\TopBannerSectionForm;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class TopBannerSectionResource extends BaseHomePageSectionResource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Home Page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Photo;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Top Banner';

    public static function sectionKey(): string
    {
        return HomePageSectionKey::TopBanner->value;
    }

    public static function form(Schema $schema): Schema
    {
        return TopBannerSectionForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTopBannerSections::route('/'),
            'create' => CreateTopBannerSection::route('/create'),
            'edit' => EditTopBannerSection::route('/{record}/edit'),
        ];
    }
}
