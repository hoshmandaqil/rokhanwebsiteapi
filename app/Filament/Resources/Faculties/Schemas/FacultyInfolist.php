<?php

namespace App\Filament\Resources\Faculties\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class FacultyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Content')
                    ->description('Main faculty information and description.')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Title')
                            ->weight(FontWeight::SemiBold)
                            ->placeholder('—')
                            ->columnSpanFull(),
                        ImageEntry::make('cover')
                            ->label('Cover image')
                            ->height(320)
                            ->columnSpanFull()
                            ->extraImgAttributes(fn (): array => ['class' => 'rounded-lg object-cover']),
                        TextEntry::make('description')
                            ->label('Description')
                            ->html()
                            ->prose()
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('dean_message')
                            ->label("Dean's message")
                            ->html()
                            ->prose()
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('mission_content')
                            ->label('Mission content')
                            ->html()
                            ->prose()
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('vision_content')
                            ->label('Vision content')
                            ->html()
                            ->prose()
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
                Section::make('Record information')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created at')
                            ->dateTime()
                            ->placeholder('—'),
                        TextEntry::make('updated_at')
                            ->label('Updated at')
                            ->dateTime()
                            ->placeholder('—'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->columnSpanFull(),
            ]);
    }
}
