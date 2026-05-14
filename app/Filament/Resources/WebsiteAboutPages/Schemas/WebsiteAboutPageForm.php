<?php

namespace App\Filament\Resources\WebsiteAboutPages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;

class WebsiteAboutPageForm
{
    public static function configure(Schema $schema, string $publicPath): Schema
    {
        return $schema
            ->components([
                Placeholder::make('hint')
                    ->label('')
                    ->content(fn (): string => 'Shown on the website at '.$publicPath.'. Use the language switcher to edit each language.')
                    ->columnSpanFull(),
                RichEditor::make('description')
                    ->label('Page content')
                    ->columnSpanFull()
                    ->required()
                    ->minLength(10)
                    ->maxLength(65000),
                FileUpload::make('image')
                    ->label('Banner image')
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(2048)
                    ->imageEditor()
                    ->columnSpanFull()
                    ->required(),
            ]);
    }
}
