<?php

namespace App\Filament\Resources\WebsiteContentPages;

use App\Enums\WebsiteContentPageKey;
use App\Filament\Concerns\ResolvesAboutFilamentRecordTitle;
use App\Filament\Resources\WebsiteContentPages\Pages\CreateWebsiteContentPage;
use App\Filament\Resources\WebsiteContentPages\Pages\EditWebsiteContentPage;
use App\Filament\Resources\WebsiteContentPages\Pages\ListWebsiteContentPages;
use App\Filament\Resources\WebsiteContentPages\Pages\ViewWebsiteContentPage;
use App\Filament\Resources\WebsiteContentPages\Schemas\WebsiteContentPageForm;
use App\Filament\Resources\WebsiteContentPages\Schemas\WebsiteContentPageInfolist;
use App\Filament\Resources\WebsiteContentPages\Tables\WebsiteContentPagesTable;
use App\Models\About;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class WebsiteContentPageResource extends Resource
{
    use ResolvesAboutFilamentRecordTitle;
    use Translatable;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = About::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'QA & Research Pages';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('page_key', array_keys(WebsiteContentPageKey::options()));
    }

    public static function form(Schema $schema): Schema
    {
        return WebsiteContentPageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WebsiteContentPageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WebsiteContentPagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWebsiteContentPages::route('/'),
            'create' => CreateWebsiteContentPage::route('/create'),
            'view' => ViewWebsiteContentPage::route('/{record}'),
            'edit' => EditWebsiteContentPage::route('/{record}/edit'),
        ];
    }
}
