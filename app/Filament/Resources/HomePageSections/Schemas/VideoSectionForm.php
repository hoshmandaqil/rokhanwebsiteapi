<?php

namespace App\Filament\Resources\HomePageSections\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VideoSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Video')
                    ->schema([
                        TextInput::make('heading')
                            ->label('Heading')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('video_embed_url')
                            ->label('YouTube embed URL')
                            ->placeholder('https://www.youtube.com/embed/...')
                            ->url()
                            ->required()
                            ->maxLength(2000),
                        Placeholder::make('hint')
                            ->label('')
                            ->content('Use an embed link (youtube.com/embed/...) so the video can play in iframe.'),
                    ]),
            ]);
    }
}
