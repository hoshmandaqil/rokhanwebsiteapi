<?php

namespace App\Filament\Resources\Careers\Schemas;

use App\Enums\EmploymentType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CareerForm
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
                TextInput::make('department')
                    ->required()
                    ->maxLength(255),
                TextInput::make('location')
                    ->required()
                    ->maxLength(255),
                Select::make('employment_type')
                    ->label('Type')
                    ->options(EmploymentType::options())
                    ->required()
                    ->native(false),
                DatePicker::make('deadline')
                    ->native(false),
                Toggle::make('is_published')
                    ->default(true)
                    ->required(),
                RichEditor::make('description')
                    ->label('Overview')
                    ->columnSpanFull(),
                RichEditor::make('responsibilities')
                    ->columnSpanFull()
                    ->required(),
                RichEditor::make('qualifications')
                    ->columnSpanFull()
                    ->required(),
                RichEditor::make('submission_guidelines')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }
}
