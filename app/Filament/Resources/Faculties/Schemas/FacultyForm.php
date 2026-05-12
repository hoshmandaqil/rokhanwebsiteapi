<?php

namespace App\Filament\Resources\Faculties\Schemas;

use App\Models\Instructor;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FacultyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->string()
                    ->minLength(2)
                    ->maxLength(255)
                    ->required()
                    ->columnSpanFull(),
                RichEditor::make('description')
                    ->columnSpanFull()
                    ->minLength(2)
                    ->maxLength(5000)
                    ->required(),
                FileUpload::make('cover')
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(1024 * 10)
                    ->imageEditor()
                    ->directory('faculties')
                    ->required()
                    ->columnSpanFull(),
                Section::make("Dean's message")
                    ->schema([
                        RichEditor::make('dean_message')
                            ->label("Dean's message")
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
                Section::make('Vision and Mission')
                    ->schema([
                        RichEditor::make('mission_content')
                            ->label('Mission')
                            ->columnSpanFull(),
                        RichEditor::make('vision_content')
                            ->label('Vision')
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
                Section::make('Faculty profile (instructors)')
                    ->description('Choose which instructors appear on the public faculty profile tab. Instructors are managed under Structure → Instructors.')
                    ->schema([
                        CheckboxList::make('faculty_profile_instructor_ids')
                            ->label('Profile instructors')
                            ->options(function ($livewire): array {
                                if (! $livewire instanceof EditRecord) {
                                    return [];
                                }
                                $record = $livewire->getRecord();
                                if ($record === null || $record->getKey() === null) {
                                    return [];
                                }

                                return Instructor::query()
                                    ->where('faculty_id', $record->getKey())
                                    ->orderBy('id')
                                    ->get()
                                    ->mapWithKeys(function (Instructor $instructor): array {
                                        $name = $instructor->getTranslation('name', app()->getLocale())
                                            ?? $instructor->getTranslation('name', config('app.fallback_locale', 'en'))
                                            ?? (is_string($instructor->name) ? $instructor->name : (string) $instructor->id);

                                        return [$instructor->id => strip_tags(is_string($name) ? $name : (string) $instructor->id)];
                                    })
                                    ->all();
                            })
                            ->columns(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull()
                    ->visible(fn ($livewire): bool => $livewire instanceof EditRecord),
            ]);
    }
}
