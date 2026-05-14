<?php

namespace App\Filament\Resources\WebsiteResearchPages;

use App\Enums\WebsiteContentPageKey;
use App\Filament\Resources\WebsiteContentPages\BaseWebsiteContentPageResource;
use App\Filament\Resources\WebsiteResearchPages\Pages\CreateResearchPublicationsWebsiteContentPage;
use App\Filament\Resources\WebsiteResearchPages\Pages\EditResearchPublicationsWebsiteContentPage;
use App\Filament\Resources\WebsiteResearchPages\Pages\ListResearchPublicationsWebsiteContentPage;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class PublicationsPageResource extends BaseWebsiteContentPageResource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Research';

    protected static ?string $navigationLabel = 'Publications';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentDuplicate;

    protected static ?string $slug = 'website-research/publications';

    public static function pageKey(): string
    {
        return WebsiteContentPageKey::ResearchPublications->value;
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResearchPublicationsWebsiteContentPage::route('/'),
            'create' => CreateResearchPublicationsWebsiteContentPage::route('/create'),
            'edit' => EditResearchPublicationsWebsiteContentPage::route('/{record}/edit'),
        ];
    }
}
