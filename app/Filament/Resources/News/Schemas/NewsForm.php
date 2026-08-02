<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, $state): void {
                        $title = is_string($state) ? $state : ($state[app()->getLocale()] ?? '');
                        if (filled($title)) {
                            $set('slug', Str::slug($title));
                        }
                    }),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Auto-generated from title; you can edit it if needed.'),
                RichEditor::make('description')
                    ->columnSpanFull()
                    ->required(),
                DatePicker::make('date')
                    ->required()
                    ->native(false),
                FileUpload::make('thumbnail')
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(2048 * 2)
                    ->imageEditor()
                    ->columnSpanFull()
                    ->required(),
            ]);
    }
}
