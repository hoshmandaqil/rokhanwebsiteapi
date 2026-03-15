<?php

namespace App\Filament\Resources\Plans\Schemas;

use App\Enums\PlanScope;
use App\Enums\PlanType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PlanForm
{
    private const FILE_MAX_SIZE_KB = 5120; // 5 MB

    private static function scopeValue(mixed $scope): ?string
    {
        return $scope instanceof PlanScope ? $scope->value : (is_string($scope) ? $scope : null);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->enum(PlanType::class)
                    ->options(collect(PlanType::cases())->mapWithKeys(fn (PlanType $type) => [$type->value => $type->label()])->all())
                    ->required()
                    ->native(false)
                    ->live(),
                Select::make('scope')
                    ->enum(PlanScope::class)
                    ->options(collect(PlanScope::cases())->mapWithKeys(fn (PlanScope $scope) => [$scope->value => $scope->label()])->all())
                    ->required()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function (Set $set): void {
                        $set('faculty_id', null);
                        $set('department_id', null);
                    }),
                Select::make('faculty_id')
                    ->label('Faculty')
                    ->relationship('faculty', 'title')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->getTranslation('title', app()->getLocale()) ?? (is_string($record->title) ? $record->title : ''))
                    ->searchable()
                    ->preload()
                    ->required(fn ($get) => self::scopeValue($get('scope')) === PlanScope::Faculty->value)
                    ->visible(fn ($get) => self::scopeValue($get('scope')) === PlanScope::Faculty->value)
                    ->native(false),
                Select::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'title')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->getTranslation('title', app()->getLocale()) ?? (is_string($record->title) ? $record->title : ''))
                    ->searchable()
                    ->preload()
                    ->required(fn ($get) => self::scopeValue($get('scope')) === PlanScope::Department->value)
                    ->visible(fn ($get) => self::scopeValue($get('scope')) === PlanScope::Department->value)
                    ->native(false),
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
                    ->columnSpanFull(),
                FileUpload::make('poster')
                    ->label('Poster image')
                    ->image()
                    ->directory('plans')
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(1024)
                    ->imageEditor()
                    ->columnSpanFull(),
                FileUpload::make('file')
                    ->label('Document (max 5 MB)')
                    ->directory('plans')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessorml.document'])
                    ->maxSize(self::FILE_MAX_SIZE_KB)
                    ->downloadable()
                    ->helperText('PDF or Word. Maximum size: 5 MB.')
                    ->columnSpanFull(),
            ]);
    }
}
