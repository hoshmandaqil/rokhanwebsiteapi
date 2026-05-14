<?php

namespace App\Filament\Resources\LeadershipMessages\Schemas;

use App\Enums\LeadershipMessageType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeadershipMessageForm
{
    /**
     * @return array<int, \Filament\Forms\Components\Component>
     */
    private static function nameTitleMessageUploads(): array
    {
        return [
            TextInput::make('name')
                ->string()
                ->minLength(3)
                ->maxLength(50)
                ->required(),
            TextInput::make('title')
                ->string()
                ->minLength(3)
                ->maxLength(50)
                ->required(),
            RichEditor::make('message')
                ->columnSpanFull()
                ->minLength(3)
                ->maxLength(5000)
                ->required(),
            FileUpload::make('image')
                ->image()
                ->acceptedFileTypes(['image/*'])
                ->maxSize(1024)
                ->imageEditor()
                ->columnSpanFull()
                ->required(),
            FileUpload::make('cover')
                ->image()
                ->acceptedFileTypes(['image/*'])
                ->maxSize(1024)
                ->imageEditor()
                ->columnSpanFull()
                ->required(),
        ];
    }

    public static function configureForFacultyRelation(Schema $schema, int $facultyId): Schema
    {
        return $schema
            ->components([
                Hidden::make('type')->default(LeadershipMessageType::Faculty->value),
                Hidden::make('faculty_id')->default($facultyId),
                Hidden::make('department_id')->default(null),
                ...self::nameTitleMessageUploads(),
            ]);
    }

    public static function configureForDepartmentRelation(Schema $schema, int $departmentId): Schema
    {
        return $schema
            ->components([
                Hidden::make('type')->default(LeadershipMessageType::Department->value),
                Hidden::make('department_id')->default($departmentId),
                Hidden::make('faculty_id')->default(null),
                ...self::nameTitleMessageUploads(),
            ]);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->enum(LeadershipMessageType::class)
                    ->options(collect(LeadershipMessageType::cases())->mapWithKeys(fn (LeadershipMessageType $type) => [$type->value => $type->label()])->all())
                    ->required()
                    ->native(false)
                    ->live(),
                Select::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'title')
                    ->required(fn ($get) => $get('type') === LeadershipMessageType::Department)
                    ->visible(fn ($get) => $get('type') === LeadershipMessageType::Department)
                    ->native(false),
                Select::make('faculty_id')
                    ->label('Faculty')
                    ->relationship('faculty', 'title')
                    ->required(fn ($get) => $get('type') === LeadershipMessageType::Faculty)
                    ->visible(fn ($get) => $get('type') === LeadershipMessageType::Faculty)
                    ->native(false),
                ...self::nameTitleMessageUploads(),
            ]);
    }
}
