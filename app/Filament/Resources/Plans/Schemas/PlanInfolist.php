<?php

namespace App\Filament\Resources\Plans\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PlanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('type')
                    ->formatStateUsing(fn ($state) => $state?->label()),
                TextEntry::make('scope')
                    ->formatStateUsing(fn ($state) => $state?->label()),
                TextEntry::make('faculty.title')
                    ->placeholder('—'),
                TextEntry::make('department.title')
                    ->placeholder('—'),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->html()
                    ->placeholder('—'),
                TextEntry::make('slug'),
                ImageEntry::make('poster')
                    ->placeholder('—'),
                TextEntry::make('file')
                    ->formatStateUsing(fn (?string $state): string => $state ? 'Uploaded' : '—')
                    ->placeholder('—'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('—'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('—'),
            ]);
    }
}
