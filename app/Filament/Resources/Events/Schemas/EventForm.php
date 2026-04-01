<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Enums\EventType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->enum(EventType::class)
                    ->options(collect(EventType::cases())->mapWithKeys(fn (EventType $type) => [$type->value => $type->label()])->all())
                    ->required()
                    ->native(false),
                TextInput::make('title')
                    ->string()
                    ->minLength(3)
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, $state): void {
                        $title = is_string($state) ? $state : ($state[app()->getLocale()] ?? '');
                        if (filled($title)) {
                            $set('slug', Str::slug($title));
                        }
                    })
                    ->required(),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Auto-generated from title; you can edit it if needed.'),
                RichEditor::make('description')
                    ->columnSpanFull()
                    ->minLength(3)
                    ->maxLength(10000)
                    ->required(),
                TextInput::make('location')
                    ->string()
                    ->maxLength(255),
                DatePicker::make('start_at')
                    ->required()
                    ->native(false),
                DatePicker::make('end_at')
                    ->native(false),
                FileUpload::make('cover')
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(2048)
                    ->imageEditor()
                    ->columnSpanFull()
                    ->required(),
                FileUpload::make('thumbnail')
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(2048)
                    ->imageEditor()
                    ->columnSpanFull()
                    ->required(),
                Toggle::make('is_published')
                    ->default(false),
            ]);
    }
}
