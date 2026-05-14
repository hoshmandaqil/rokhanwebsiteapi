<?php

namespace App\Filament\Resources\WebsiteResearchPages;

use App\Enums\WebsiteContentPageKey;
use App\Filament\Resources\WebsiteContentPages\BaseWebsiteContentPageResource;
use App\Filament\Resources\WebsiteResearchPages\Pages\CreateResearchPolicyWebsiteContentPage;
use App\Filament\Resources\WebsiteResearchPages\Pages\EditResearchPolicyWebsiteContentPage;
use App\Filament\Resources\WebsiteResearchPages\Pages\ListResearchPolicyWebsiteContentPage;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class PolicyPageResource extends BaseWebsiteContentPageResource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Research';

    protected static ?string $navigationLabel = 'Policy';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Scale;

    protected static ?string $slug = 'website-research/policy';

    public static function pageKey(): string
    {
        return WebsiteContentPageKey::ResearchPolicy->value;
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResearchPolicyWebsiteContentPage::route('/'),
            'create' => CreateResearchPolicyWebsiteContentPage::route('/create'),
            'edit' => EditResearchPolicyWebsiteContentPage::route('/{record}/edit'),
        ];
    }
}
