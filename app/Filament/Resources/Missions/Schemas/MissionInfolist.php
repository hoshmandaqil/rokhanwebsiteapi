<?php

namespace App\Filament\Resources\Missions\Schemas;

use App\Enums\AboutType;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Mission')
                    ->schema([
                        TextEntry::make('type')
                            ->formatStateUsing(fn (AboutType $state): string => $state->label()),
                        TextEntry::make('faculty.title')
                            ->label('Program')
                            ->placeholder('—')
                            ->visible(fn ($record) => $record?->type === AboutType::Program),
                        TextEntry::make('department.title')
                            ->placeholder('—')
                            ->visible(fn ($record) => $record?->type === AboutType::Department),
                        TextEntry::make('content')
                            ->label('Content')
                            ->html()
                            ->prose()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }
}
