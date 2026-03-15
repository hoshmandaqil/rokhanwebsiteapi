<?php

namespace App\Filament\Resources\Visions\Schemas;

use App\Enums\AboutType;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class VisionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Level')
                    ->enum(AboutType::class)
                    ->options(collect(AboutType::cases())->mapWithKeys(fn (AboutType $type) => [$type->value => $type->label()])->all())
                    ->required()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function (Set $set): void {
                        $set('faculty_id', null);
                        $set('department_id', null);
                    }),
                Select::make('faculty_id')
                    ->label('Program')
                    ->relationship('faculty', 'title')
                    ->required(fn ($get) => $get('type') === AboutType::Program)
                    ->visible(fn ($get) => $get('type') === AboutType::Program)
                    ->native(false)
                    ->searchable()
                    ->preload(),
                Select::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'title')
                    ->required(fn ($get) => $get('type') === AboutType::Department)
                    ->visible(fn ($get) => $get('type') === AboutType::Department)
                    ->native(false)
                    ->searchable()
                    ->preload(),
                RichEditor::make('content')
                    ->label('Vision')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
