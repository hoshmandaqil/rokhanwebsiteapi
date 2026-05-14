<?php

namespace App\Filament\Resources\WebsiteResearchPages;

use App\Enums\WebsiteContentPageKey;
use App\Filament\Resources\WebsiteContentPages\BaseWebsiteContentPageResource;
use App\Filament\Resources\WebsiteResearchPages\Pages\CreateResearchStructureWebsiteContentPage;
use App\Filament\Resources\WebsiteResearchPages\Pages\EditResearchStructureWebsiteContentPage;
use App\Filament\Resources\WebsiteResearchPages\Pages\ListResearchStructureWebsiteContentPage;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class StructurePageResource extends BaseWebsiteContentPageResource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Research';

    protected static ?string $navigationLabel = 'Structure';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Squares2x2;

    protected static ?string $slug = 'website-research/structure';

    public static function pageKey(): string
    {
        return WebsiteContentPageKey::ResearchStructure->value;
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResearchStructureWebsiteContentPage::route('/'),
            'create' => CreateResearchStructureWebsiteContentPage::route('/create'),
            'edit' => EditResearchStructureWebsiteContentPage::route('/{record}/edit'),
        ];
    }
}
