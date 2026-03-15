<?php

namespace App\Filament\Resources\Abouts\Schemas;

use App\Enums\AboutType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('type')
                    ->default(AboutType::History),
                TextInput::make('title')
                    ->columnSpanFull()
                    ->minLength(10)
                    ->maxLength(100)
                    ->required(),
                RichEditor::make('description')
                    ->columnSpanFull()
                    ->minLength(10)
                    ->maxLength(1000)
                    ->required(),
                FileUpload::make('image')
                    ->label(__('filament/about.fields.image'))
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(1024)
                    ->imageEditor()
                    ->columnSpanFull()
                    ->required(),
            ]);
    }
}
