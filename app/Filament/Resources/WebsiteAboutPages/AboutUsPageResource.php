<?php

namespace App\Filament\Resources\WebsiteAboutPages;

use App\Enums\AboutPageKey;
use App\Filament\Resources\WebsiteAboutPages\Concerns\ManagesSingletonWebsiteAboutPage;
use App\Filament\Resources\WebsiteAboutPages\Pages\CreateAboutUsWebsiteAboutPage;
use App\Filament\Resources\WebsiteAboutPages\Pages\EditAboutUsWebsiteAboutPage;
use App\Filament\Resources\WebsiteAboutPages\Pages\ListAboutUsWebsiteAboutPage;
use App\Filament\Resources\WebsiteAboutPages\Schemas\WebsiteAboutPageForm;
use App\Filament\Resources\WebsiteAboutPages\Tables\WebsiteAboutPageTable;
use App\Models\About;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class AboutUsPageResource extends Resource
{
    use ManagesSingletonWebsiteAboutPage;
    use Translatable;

    protected static ?string $model = About::class;

    protected static string|\UnitEnum|null $navigationGroup = 'About';

    protected static ?string $navigationLabel = 'About Us';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::InformationCircle;

    protected static ?string $slug = 'website-about/about-us';

    protected static ?string $recordTitleAttribute = 'title';

    public static function pageKey(): string
    {
        return AboutPageKey::AboutUs->value;
    }

    public static function form(Schema $schema): Schema
    {
        return WebsiteAboutPageForm::configure($schema, '/about/'.static::pageKey());
    }

    public static function table(Table $table): Table
    {
        return WebsiteAboutPageTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAboutUsWebsiteAboutPage::route('/'),
            'create' => CreateAboutUsWebsiteAboutPage::route('/create'),
            'edit' => EditAboutUsWebsiteAboutPage::route('/{record}/edit'),
        ];
    }
}
