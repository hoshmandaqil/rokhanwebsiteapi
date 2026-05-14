<?php

namespace App\Filament\Resources\WebsiteResearchPages;

use App\Enums\WebsiteContentPageKey;
use App\Filament\Resources\WebsiteContentPages\BaseWebsiteContentPageResource;
use App\Filament\Resources\WebsiteResearchPages\Pages\CreateResearchJournalsWebsiteContentPage;
use App\Filament\Resources\WebsiteResearchPages\Pages\EditResearchJournalsWebsiteContentPage;
use App\Filament\Resources\WebsiteResearchPages\Pages\ListResearchJournalsWebsiteContentPage;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class JournalsPageResource extends BaseWebsiteContentPageResource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Research';

    protected static ?string $navigationLabel = 'Journals';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BookOpen;

    protected static ?string $slug = 'website-research/journals';

    public static function pageKey(): string
    {
        return WebsiteContentPageKey::ResearchJournals->value;
    }

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResearchJournalsWebsiteContentPage::route('/'),
            'create' => CreateResearchJournalsWebsiteContentPage::route('/create'),
            'edit' => EditResearchJournalsWebsiteContentPage::route('/{record}/edit'),
        ];
    }
}
