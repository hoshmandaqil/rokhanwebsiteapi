<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
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
                TextInput::make('slug')
                    ->string()
                    ->label('Slug')
                    ->helperText('Used in the public department URL. Leave blank to generate from the title.')
                    ->maxLength(255)
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
                    ->maxSize(1024 * 10)->imageEditor()
                    ->directory('departments')
                    ->required()
                    ->columnSpanFull(),
                Select::make('faculty_id')
                    ->label('Faculty')
                    ->relationship('faculty', 'title')
                    ->required(),
                Section::make('Vision and Mission')
                    ->schema([
                        RichEditor::make('vision_content')
                            ->label('Vision')
                            ->columnSpanFull(),
                        RichEditor::make('mission_content')
                            ->label('Mission')
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
            ]);
    }
}
