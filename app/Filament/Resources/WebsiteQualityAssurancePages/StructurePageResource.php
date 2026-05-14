<?php

namespace App\Filament\Resources\WebsiteQualityAssurancePages;

use App\Enums\WebsiteContentPageKey;
use App\Filament\Resources\WebsiteContentPages\BaseWebsiteContentPageResource;
use App\Filament\Resources\WebsiteQualityAssurancePages\Pages\CreateStructureQaWebsiteContentPage;
use App\Filament\Resources\WebsiteQualityAssurancePages\Pages\EditStructureQaWebsiteContentPage;
use App\Filament\Resources\WebsiteQualityAssurancePages\Pages\ListStructureQaWebsiteContentPage;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class StructurePageResource extends BaseWebsiteContentPageResource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Quality Assurance';

    protected static ?string $navigationLabel = 'Structure';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Squares2x2;

    protected static ?string $slug = 'website-quality-assurance/structure';

    public static function pageKey(): string
    {
        return WebsiteContentPageKey::QaStructure->value;
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStructureQaWebsiteContentPage::route('/'),
            'create' => CreateStructureQaWebsiteContentPage::route('/create'),
            'edit' => EditStructureQaWebsiteContentPage::route('/{record}/edit'),
        ];
    }
}
