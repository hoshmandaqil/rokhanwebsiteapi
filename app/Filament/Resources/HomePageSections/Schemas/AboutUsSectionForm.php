<?php

namespace App\Filament\Resources\HomePageSections\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AboutUsSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Heading content')
                    ->schema([
                        TextInput::make('subheading')
                            ->label('Small title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('heading')
                            ->label('Main title')
                            ->required()
                            ->maxLength(255),
                        RichEditor::make('description')
                            ->label('Lead text')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('stat_title')
                            ->label('Stats section title')
                            ->required()
                            ->maxLength(255),
                    ]),

                Section::make('Vision and mission text')
                    ->schema([
                        TextInput::make('body_primary')
                            ->label('Section heading')
                            ->required()
                            ->maxLength(255),
                        RichEditor::make('body_secondary')
                            ->label('Section content (paragraphs)')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Images (4 placeholders)')
                    ->schema([
                        FileUpload::make('image_one')
                            ->label('Image 1')
                            ->image()
                            ->directory('home-page/about')
                            ->acceptedFileTypes(['image/*'])
                            ->maxSize(3072)
                            ->imageEditor()
                            ->required(),
                        FileUpload::make('image_two')
                            ->label('Image 2')
                            ->image()
                            ->directory('home-page/about')
                            ->acceptedFileTypes(['image/*'])
                            ->maxSize(3072)
                            ->imageEditor()
                            ->required(),
                        FileUpload::make('image_three')
                            ->label('Image 3')
                            ->image()
                            ->directory('home-page/about')
                            ->acceptedFileTypes(['image/*'])
                            ->maxSize(3072)
                            ->imageEditor()
                            ->required(),
                        FileUpload::make('image_four')
                            ->label('Image 4')
                            ->image()
                            ->directory('home-page/about')
                            ->acceptedFileTypes(['image/*'])
                            ->maxSize(3072)
                            ->imageEditor()
                            ->required(),
                    ]),

                Section::make('Stats')
                    ->schema([
                        TextInput::make('stat_one_label')->label('Stat 1 label')->required()->maxLength(255),
                        TextInput::make('stat_one_value')->label('Stat 1 value')->required()->maxLength(40),
                        TextInput::make('stat_two_label')->label('Stat 2 label')->required()->maxLength(255),
                        TextInput::make('stat_two_value')->label('Stat 2 value')->required()->maxLength(40),
                        TextInput::make('stat_three_label')->label('Stat 3 label')->required()->maxLength(255),
                        TextInput::make('stat_three_value')->label('Stat 3 value')->required()->maxLength(40),
                        TextInput::make('stat_four_label')->label('Stat 4 label')->required()->maxLength(255),
                        TextInput::make('stat_four_value')->label('Stat 4 value')->required()->maxLength(40),
                    ]),
            ]);
    }
}
