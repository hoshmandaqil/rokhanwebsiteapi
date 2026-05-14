<?php

namespace App\Filament\Resources\WebsiteBanners;

use App\Filament\Resources\WebsiteBanners\Pages\EditWebsiteBanner;
use App\Filament\Resources\WebsiteBanners\Pages\ListWebsiteBanners;
use App\Filament\Resources\WebsiteBanners\Schemas\WebsiteBannerForm;
use App\Filament\Resources\WebsiteBanners\Tables\WebsiteBannersTable;
use App\Models\WebsiteBanner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class WebsiteBannerResource extends Resource
{
    protected static ?string $model = WebsiteBanner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::RectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Website Banners';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return WebsiteBannerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WebsiteBannersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWebsiteBanners::route('/'),
            'edit' => EditWebsiteBanner::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
