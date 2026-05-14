<?php

namespace App\Filament\Resources\HomePageSections\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TopBannerSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Top banner')
                    ->schema([
                        Placeholder::make('hint')
                            ->label('')
                            ->content('This image is used in the home page hero banner.'),
                        FileUpload::make('image')
                            ->label('Banner image')
                            ->image()
                            ->directory('home-page')
                            ->acceptedFileTypes(['image/*'])
                            ->maxSize(3072)
                            ->imageEditor()
                            ->required(),
                    ]),
            ]);
    }
}
