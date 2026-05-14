<?php

namespace App\Filament\Resources\WebsiteBanners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WebsiteBannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Banner')
                    ->schema([
                        Placeholder::make('info')
                            ->label('')
                            ->content('This image is shown at the top of the matching public page (listing or static sub-page), not on individual news posts or similar dynamic detail pages.'),
                        TextInput::make('slug')
                            ->label('Page key')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('name')
                            ->label('Page title (reference)')
                            ->disabled()
                            ->dehydrated(false),
                        FileUpload::make('image')
                            ->label('Banner image')
                            ->image()
                            ->directory('website-banners')
                            ->acceptedFileTypes(['image/*'])
                            ->maxSize(4096)
                            ->imageEditor()
                            ->nullable()
                            ->helperText('Leave empty to use the website default image for this page.'),
                    ]),
            ]);
    }
}
