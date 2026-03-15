<?php

namespace App\Filament\Resources\Abouts\Schemas;

use App\Enums\AboutType;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class AboutInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament/about.sections.content'))
                    ->description(__('filament/about.sections.content_description'))
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
                        TextEntry::make('title')
                            ->label(__('filament/about.fields.title'))
                            ->placeholder('-')
                            ->weight(FontWeight::SemiBold),
                        TextEntry::make('description')
                            ->label(__('filament/about.fields.description'))
                            ->html()
                            ->prose()
                            ->placeholder('-')
                            ->columnSpanFull(),
                        ImageEntry::make('image')
                            ->label(__('filament/about.fields.image'))
                            ->height(280)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
                Section::make(__('filament/about.sections.meta'))
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('filament/about.fields.created_at'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label(__('filament/about.fields.updated_at'))
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
