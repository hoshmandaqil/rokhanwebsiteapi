<?php

namespace App\Filament\Resources\Facilities;

use App\Filament\Resources\Facilities\Pages\CreateFacility;
use App\Filament\Resources\Facilities\Pages\EditFacility;
use App\Filament\Resources\Facilities\Pages\ListFacilities;
use App\Filament\Resources\Facilities\Pages\ViewFacility;
use App\Filament\Resources\Facilities\Schemas\FacilityForm;
use App\Filament\Resources\Facilities\Schemas\FacilityInfolist;
use App\Filament\Resources\Facilities\Tables\FacilitiesTable;
use App\Models\Facility;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class FacilityResource extends Resource
{
    use Translatable;

    protected static ?string $model = Facility::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingLibrary;

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Facilities';

    public static function form(Schema $schema): Schema
    {
        return FacilityForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FacilityInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FacilitiesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFacilities::route('/'),
            'create' => CreateFacility::route('/create'),
            'view' => ViewFacility::route('/{record}'),
            'edit' => EditFacility::route('/{record}/edit'),
        ];
    }
}
