<?php

namespace App\Filament\Resources\WebsiteAboutPages;

use App\Enums\AboutPageKey;
use App\Filament\Resources\WebsiteAboutPages\Concerns\ManagesSingletonWebsiteAboutPage;
use App\Filament\Resources\WebsiteAboutPages\Pages\CreateStrategicPlanWebsiteAboutPage;
use App\Filament\Resources\WebsiteAboutPages\Pages\EditStrategicPlanWebsiteAboutPage;
use App\Filament\Resources\WebsiteAboutPages\Pages\ListStrategicPlanWebsiteAboutPage;
use App\Filament\Resources\WebsiteAboutPages\Schemas\WebsiteAboutPageForm;
use App\Filament\Resources\WebsiteAboutPages\Tables\WebsiteAboutPageTable;
use App\Models\About;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class StrategicPlanPageResource extends Resource
{
    use ManagesSingletonWebsiteAboutPage;
    use Translatable;

    protected static ?string $model = About::class;

    protected static string|\UnitEnum|null $navigationGroup = 'About';

    protected static ?string $navigationLabel = 'Strategic Plan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    protected static ?string $slug = 'website-about/strategic-plan';

    protected static ?string $recordTitleAttribute = 'title';

    public static function pageKey(): string
    {
        return AboutPageKey::StrategicPlan->value;
    }

    public static function form(Schema $schema): Schema
    {
        return WebsiteAboutPageForm::configure($schema, '/about/'.static::pageKey(), withDocuments: true);
    }

    public static function table(Table $table): Table
    {
        return WebsiteAboutPageTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStrategicPlanWebsiteAboutPage::route('/'),
            'create' => CreateStrategicPlanWebsiteAboutPage::route('/create'),
            'edit' => EditStrategicPlanWebsiteAboutPage::route('/{record}/edit'),
        ];
    }
}
