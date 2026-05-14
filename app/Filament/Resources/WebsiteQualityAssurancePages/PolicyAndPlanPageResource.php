<?php

namespace App\Filament\Resources\WebsiteQualityAssurancePages;

use App\Enums\WebsiteContentPageKey;
use App\Filament\Resources\WebsiteContentPages\BaseWebsiteContentPageResource;
use App\Filament\Resources\WebsiteQualityAssurancePages\Pages\CreatePolicyAndPlanQaWebsiteContentPage;
use App\Filament\Resources\WebsiteQualityAssurancePages\Pages\EditPolicyAndPlanQaWebsiteContentPage;
use App\Filament\Resources\WebsiteQualityAssurancePages\Pages\ListPolicyAndPlanQaWebsiteContentPage;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class PolicyAndPlanPageResource extends BaseWebsiteContentPageResource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Quality Assurance';

    protected static ?string $navigationLabel = 'Policy And Plan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static ?string $slug = 'website-quality-assurance/policy-and-plan';

    public static function pageKey(): string
    {
        return WebsiteContentPageKey::QaPolicyAndPlan->value;
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPolicyAndPlanQaWebsiteContentPage::route('/'),
            'create' => CreatePolicyAndPlanQaWebsiteContentPage::route('/create'),
            'edit' => EditPolicyAndPlanQaWebsiteContentPage::route('/{record}/edit'),
        ];
    }
}
