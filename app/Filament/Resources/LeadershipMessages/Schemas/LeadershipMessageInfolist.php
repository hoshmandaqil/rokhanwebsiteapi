<?php

namespace App\Filament\Resources\LeadershipMessages\Schemas;

use App\Enums\LeadershipMessageType;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LeadershipMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('type')
                    ->formatStateUsing(fn (LeadershipMessageType $state): string => $state->label()),
                TextEntry::make('department.title')
                    ->label('Department')
                    ->placeholder('—'),
                TextEntry::make('faculty.title')
                    ->label('Faculty')
                    ->placeholder('—'),
                TextEntry::make('name'),
                TextEntry::make('title'),
                ImageEntry::make('image'),
                ImageEntry::make('cover'),
                TextEntry::make('message')
                    ->html(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('—'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('—'),
            ]);
    }
}
