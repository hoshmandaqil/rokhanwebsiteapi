<?php

namespace App\Filament\Resources\Faculties\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FacultyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->string()
                    ->minLength(2)
                    ->maxLength(255)
                    ->required()
                    ->columnSpanFull(),
                RichEditor::make('description')
                    ->columnSpanFull()
                    ->minLength(2)
                    ->maxLength(5000)
                    ->required(),
                FileUpload::make('cover')
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(1024 * 10)
                    ->imageEditor()
                    ->directory('faculties')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
