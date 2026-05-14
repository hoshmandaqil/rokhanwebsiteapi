<?php

namespace App\Filament\Resources\WebsiteQualityAssurancePages;

use App\Enums\WebsiteContentPageKey;
use App\Filament\Resources\WebsiteContentPages\BaseWebsiteContentPageResource;
use App\Filament\Resources\WebsiteQualityAssurancePages\Pages\CreateAboutQaWebsiteContentPage;
use App\Filament\Resources\WebsiteQualityAssurancePages\Pages\EditAboutQaWebsiteContentPage;
use App\Filament\Resources\WebsiteQualityAssurancePages\Pages\ListAboutQaWebsiteContentPage;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class AboutQaPageResource extends BaseWebsiteContentPageResource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Quality Assurance';

    protected static ?string $navigationLabel = 'About QA';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::InformationCircle;

    protected static ?string $slug = 'website-quality-assurance/about';

    public static function pageKey(): string
    {
        return WebsiteContentPageKey::QaAbout->value;
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAboutQaWebsiteContentPage::route('/'),
            'create' => CreateAboutQaWebsiteContentPage::route('/create'),
            'edit' => EditAboutQaWebsiteContentPage::route('/{record}/edit'),
        ];
    }
}
