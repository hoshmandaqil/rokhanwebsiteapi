<?php

namespace App\Filament\Resources\HomePageSections;

use App\Enums\HomePageSectionKey;
use App\Filament\Resources\HomePageSections\Pages\CreateVideoSection;
use App\Filament\Resources\HomePageSections\Pages\EditVideoSection;
use App\Filament\Resources\HomePageSections\Pages\ListVideoSections;
use App\Filament\Resources\HomePageSections\Schemas\VideoSectionForm;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class VideoSectionResource extends BaseHomePageSectionResource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Home Page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Play;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Video';

    public static function sectionKey(): string
    {
        return HomePageSectionKey::Video->value;
    }

    public static function form(Schema $schema): Schema
    {
        return VideoSectionForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVideoSections::route('/'),
            'create' => CreateVideoSection::route('/create'),
            'edit' => EditVideoSection::route('/{record}/edit'),
        ];
    }
}
