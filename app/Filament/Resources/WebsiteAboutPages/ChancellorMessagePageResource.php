<?php

namespace App\Filament\Resources\WebsiteAboutPages;

use App\Enums\AboutPageKey;
use App\Filament\Resources\WebsiteAboutPages\Concerns\ManagesSingletonWebsiteAboutPage;
use App\Filament\Resources\WebsiteAboutPages\Pages\CreateChancellorMessageWebsiteAboutPage;
use App\Filament\Resources\WebsiteAboutPages\Pages\EditChancellorMessageWebsiteAboutPage;
use App\Filament\Resources\WebsiteAboutPages\Pages\ListChancellorMessageWebsiteAboutPage;
use App\Filament\Resources\WebsiteAboutPages\Schemas\WebsiteAboutPageForm;
use App\Filament\Resources\WebsiteAboutPages\Tables\WebsiteAboutPageTable;
use App\Models\About;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class ChancellorMessagePageResource extends Resource
{
    use ManagesSingletonWebsiteAboutPage;
    use Translatable;

    protected static ?string $model = About::class;

    protected static string|\UnitEnum|null $navigationGroup = 'About';

    protected static ?string $navigationLabel = 'Chancellor Message';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static ?string $slug = 'website-about/chancellor-message';

    protected static ?string $recordTitleAttribute = 'title';

    public static function pageKey(): string
    {
        return AboutPageKey::ChancellorMessage->value;
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
            'index' => ListChancellorMessageWebsiteAboutPage::route('/'),
            'create' => CreateChancellorMessageWebsiteAboutPage::route('/create'),
            'edit' => EditChancellorMessageWebsiteAboutPage::route('/{record}/edit'),
        ];
    }
}
