<?php

namespace App\Filament\Resources\Instructors\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InstructorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('faculty_id')
                    ->label('Faculty')
                    ->relationship('faculty', 'title')
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('photo')
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(1024 * 10)
                    ->imageEditor()
                    ->directory('instructors')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
