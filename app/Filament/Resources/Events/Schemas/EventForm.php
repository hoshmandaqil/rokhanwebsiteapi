<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Enums\EventType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

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
                    ->required(),
                RichEditor::make('description')
                    ->columnSpanFull()
                    ->minLength(3)
                    ->maxLength(10000)
                    ->required(),
                TextInput::make('location')
                    ->string()
                    ->maxLength(255),
                DateTimePicker::make('start_at')
                    ->required()
                    ->native(false),
                DateTimePicker::make('end_at')
                    ->native(false),
                FileUpload::make('cover')
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(1024)
                    ->imageEditor()
                    ->columnSpanFull()
                    ->required(),
                FileUpload::make('thumbnail')
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(1024)
                    ->imageEditor()
                    ->columnSpanFull()
                    ->required(),
                Toggle::make('is_published')
                    ->default(false),
            ]);
    }
}
