<?php

namespace App\Filament\Resources\Abouts;

use App\Filament\Concerns\ResolvesAboutFilamentRecordTitle;
use App\Filament\Resources\Abouts\Pages\CreateHistory;
use App\Filament\Resources\Abouts\Pages\EditHistory;
use App\Filament\Resources\Abouts\Pages\ListHistory;
use App\Filament\Resources\Abouts\Pages\ViewHistory;
use App\Filament\Resources\Abouts\Schemas\AboutInfolist;
use App\Filament\Resources\Abouts\Schemas\HistoryForm;
use App\Filament\Resources\Abouts\Tables\HistoryTable;
use App\Models\About;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class HistoryResource extends Resource
{
    use ResolvesAboutFilamentRecordTitle;
    use Translatable;

    protected static ?string $model = About::class;

    protected static string|\UnitEnum|null $navigationGroup = 'About';

    protected static ?string $navigationLabel = 'History';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Clock;

    protected static ?string $recordTitleAttribute = 'title';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'history';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->history();
    }

    public static function form(Schema $schema): Schema
    {
        return HistoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AboutInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HistoryTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHistory::route('/'),
            'create' => CreateHistory::route('/create'),
            'view' => ViewHistory::route('/{record}'),
            'edit' => EditHistory::route('/{record}/edit'),
        ];
    }
}
