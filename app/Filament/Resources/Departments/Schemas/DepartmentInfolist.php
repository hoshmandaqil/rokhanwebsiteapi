<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class DepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Content')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Title')
                            ->weight(FontWeight::SemiBold)
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('faculty.title')
                            ->label('Program')
                            ->placeholder('—'),
                        ImageEntry::make('cover')
                            ->label('Cover')
                            ->height(320)
                            ->columnSpanFull()
                            ->extraImgAttributes(fn (): array => ['class' => 'rounded-lg object-cover']),
                        TextEntry::make('description')
                            ->label('Description')
                            ->html()
                            ->prose()
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
                Section::make('Record information')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('—'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('—'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
