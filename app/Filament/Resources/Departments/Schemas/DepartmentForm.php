<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->string()
                    ->label('Title')
                    ->required()
                    ->columnSpanFull(),
                RichEditor::make('description')
                    ->label('Description')
                    ->columnSpanFull()
                    ->minLength(2)
                    ->maxLength(5000)
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('cover')
                    ->label('Cover')
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(1024) ->imageEditor()
                    ->directory('departments')
                    ->required()
                    ->columnSpanFull(),
                Select::make('faculty_id')
                    ->label('Faculty')
                    ->relationship('faculty', 'title')
                    ->required(),
            ]);
    }
}
