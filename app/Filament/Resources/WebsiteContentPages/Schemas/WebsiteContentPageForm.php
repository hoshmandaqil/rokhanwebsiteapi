<?php

namespace App\Filament\Resources\WebsiteContentPages\Schemas;

use App\Enums\AboutType;
use App\Enums\WebsiteContentPageKey;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WebsiteContentPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('type')
                    ->default(AboutType::University->value),
                Select::make('page_key')
                    ->label('Page')
                    ->required()
                    ->options(WebsiteContentPageKey::options())
                    ->native(false)
                    ->unique(ignoreRecord: true),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Placeholder::make('hint')
                    ->label('')
                    ->content('Use the language switcher to edit each language. This content powers the Quality Assurance and Research website pages.')
                    ->columnSpanFull(),
                RichEditor::make('description')
                    ->label('Page content')
                    ->columnSpanFull()
                    ->required()
                    ->maxLength(65000),
                FileUpload::make('image')
                    ->label('Banner image')
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(2048)
                    ->imageEditor()
                    ->columnSpanFull(),
            ]);
    }
}
