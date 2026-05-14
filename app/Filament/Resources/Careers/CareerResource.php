<?php

namespace App\Filament\Resources\Careers;

use App\Filament\Resources\Careers\Pages\CreateCareer;
use App\Filament\Resources\Careers\Pages\EditCareer;
use App\Filament\Resources\Careers\Pages\ListCareers;
use App\Filament\Resources\Careers\Pages\ViewCareer;
use App\Filament\Resources\Careers\Schemas\CareerForm;
use App\Filament\Resources\Careers\Schemas\CareerInfolist;
use App\Filament\Resources\Careers\Tables\CareersTable;
use App\Models\Career;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class CareerResource extends Resource
{
    use Translatable;

    protected static ?string $model = Career::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Briefcase;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Careers';

    public static function form(Schema $schema): Schema
    {
        return CareerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CareerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CareersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCareers::route('/'),
            'create' => CreateCareer::route('/create'),
            'view' => ViewCareer::route('/{record}'),
            'edit' => EditCareer::route('/{record}/edit'),
        ];
    }
}
