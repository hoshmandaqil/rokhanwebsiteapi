<?php

namespace App\Filament\Resources\WebsiteResearchPages;

use App\Enums\WebsiteContentPageKey;
use App\Filament\Resources\WebsiteContentPages\BaseWebsiteContentPageResource;
use App\Filament\Resources\WebsiteResearchPages\Pages\CreateResearchOverviewWebsiteContentPage;
use App\Filament\Resources\WebsiteResearchPages\Pages\EditResearchOverviewWebsiteContentPage;
use App\Filament\Resources\WebsiteResearchPages\Pages\ListResearchOverviewWebsiteContentPage;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class OverviewPageResource extends BaseWebsiteContentPageResource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Research';

    protected static ?string $navigationLabel = 'Overview';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static ?string $slug = 'website-research/overview';

    public static function pageKey(): string
    {
        return WebsiteContentPageKey::ResearchOverview->value;
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResearchOverviewWebsiteContentPage::route('/'),
            'create' => CreateResearchOverviewWebsiteContentPage::route('/create'),
            'edit' => EditResearchOverviewWebsiteContentPage::route('/{record}/edit'),
        ];
    }
}
