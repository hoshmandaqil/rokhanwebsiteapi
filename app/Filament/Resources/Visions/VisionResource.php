<?php

namespace App\Filament\Resources\Visions;

use App\Filament\Resources\Visions\Pages\CreateVision;
use App\Filament\Resources\Visions\Pages\EditVision;
use App\Filament\Resources\Visions\Pages\ListVisions;
use App\Filament\Resources\Visions\Pages\ViewVision;
use App\Filament\Resources\Visions\Schemas\VisionForm;
use App\Filament\Resources\Visions\Schemas\VisionInfolist;
use App\Filament\Resources\Visions\Tables\VisionsTable;
use App\Models\Vision;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class VisionResource extends Resource
{
    use Translatable;

    protected static ?string $model = Vision::class;

    protected static string|\UnitEnum|null $navigationGroup = 'About';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Eye;

    protected static ?string $recordTitleAttribute = 'content';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return VisionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VisionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VisionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVisions::route('/'),
            'create' => CreateVision::route('/create'),
            'view' => ViewVision::route('/{record}'),
            'edit' => EditVision::route('/{record}/edit'),
        ];
    }
}
