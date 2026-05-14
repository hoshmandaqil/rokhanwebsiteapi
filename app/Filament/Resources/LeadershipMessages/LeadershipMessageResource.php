<?php

namespace App\Filament\Resources\LeadershipMessages;

use App\Filament\Resources\LeadershipMessages\Pages\CreateLeadershipMessage;
use App\Filament\Resources\LeadershipMessages\Pages\EditLeadershipMessage;
use App\Filament\Resources\LeadershipMessages\Pages\ListLeadershipMessages;
use App\Filament\Resources\LeadershipMessages\Pages\ViewLeadershipMessage;
use App\Filament\Resources\LeadershipMessages\Schemas\LeadershipMessageForm;
use App\Filament\Resources\LeadershipMessages\Schemas\LeadershipMessageInfolist;
use App\Filament\Resources\LeadershipMessages\Tables\LeadershipMessagesTable;
use App\Models\LeadershipMessage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class LeadershipMessageResource extends Resource
{
    use Translatable;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = LeadershipMessage::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Leadership';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Leadership Messages';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return LeadershipMessageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LeadershipMessageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeadershipMessagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeadershipMessages::route('/'),
            'create' => CreateLeadershipMessage::route('/create'),
            'view' => ViewLeadershipMessage::route('/{record}'),
            'edit' => EditLeadershipMessage::route('/{record}/edit'),
        ];
    }
}
