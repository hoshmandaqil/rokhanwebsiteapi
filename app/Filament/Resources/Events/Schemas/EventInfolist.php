<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Enums\EventType;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('type')
                    ->formatStateUsing(fn (EventType $state): string => $state->label()),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->html()
                    ->placeholder('—'),
                TextEntry::make('location')
                    ->placeholder('—'),
                TextEntry::make('start_at')
                    ->dateTime(),
                TextEntry::make('end_at')
                    ->dateTime()
                    ->placeholder('—'),
                ImageEntry::make('cover'),
                ImageEntry::make('thumbnail'),
                TextEntry::make('is_published')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('—'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('—'),
            ]);
    }
}
