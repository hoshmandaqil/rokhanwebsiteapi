<?php

namespace App\Filament\Resources\Programs\Schemas;

use App\Enums\ProgramLevel;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('level')
                    ->label('Program level')
                    ->options(collect(ProgramLevel::cases())->mapWithKeys(fn (ProgramLevel $l) => [$l->value => $l->label()]))
                    ->required()
                    ->native(false),
                Select::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'title')
                    ->searchable()
                    ->preload()
                    ->native(false),
                TextInput::make('slug')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Leave blank to auto-generate from the title.'),
                TextInput::make('title')
                    ->required()
                    ->maxLength(500)
                    ->columnSpanFull(),
                RichEditor::make('description')
                    ->columnSpanFull()
                    ->required(),
                TextInput::make('duration')
                    ->maxLength(120),
                TextInput::make('degree')
                    ->label('Degree / award title')
                    ->maxLength(500)
                    ->columnSpanFull(),
                FileUpload::make('cover')
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(1024 * 10)
                    ->imageEditor()
                    ->directory('programs')
                    ->columnSpanFull(),
                FileUpload::make('thumbnail')
                    ->image()
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(1024 * 10)
                    ->imageEditor()
                    ->directory('programs')
                    ->columnSpanFull(),
                Repeater::make('highlights')
                    ->label('Program highlights')
                    ->helperText('Short points that appear on the program page. Each item can use formatting.')
                    ->schema([
                        RichEditor::make('content')
                            ->label('Highlight')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                                'link',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->defaultItems(0)
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => filled($state['content'] ?? null)
                        ? trim(strip_tags((string) $state['content'])) ?: 'Highlight'
                        : 'Highlight')
                    ->columnSpanFull()
                    ->formatStateUsing(function ($state): array {
                        if (! is_array($state)) {
                            return [];
                        }
                        $out = [];
                        foreach ($state as $row) {
                            if (is_string($row)) {
                                $out[] = ['content' => $row];

                                continue;
                            }
                            if (is_array($row)) {
                                $out[] = ['content' => (string) ($row['content'] ?? $row['body'] ?? '')];
                            }
                        }

                        return $out;
                    })
                    ->dehydrateStateUsing(function ($state): array {
                        if (! is_array($state)) {
                            return [];
                        }

                        return collect($state)
                            ->map(fn ($row) => is_array($row) ? trim((string) ($row['content'] ?? '')) : '')
                            ->filter(fn (string $html) => $html !== '')
                            ->values()
                            ->all();
                    }),
                TagsInput::make('program_values')
                    ->label('Core values')
                    ->helperText('Optional bullet labels for the Vision & Mission tab (one value per tag).')
                    ->separator(',')
                    ->columnSpanFull(),
                Section::make('Vision & mission (rich text)')
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
                Section::make('Academic structure')
                    ->description('Shown on the Academic Structure tab as formatted content.')
                    ->schema([
                        RichEditor::make('academic_structure_html')
                            ->label('Academic structure')
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
                Section::make('Fee structure')
                    ->description('Shown on the Fee Structure tab as formatted content.')
                    ->schema([
                        RichEditor::make('fee_structure_html')
                            ->label('Fee structure')
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Section::make('Publishing')
                    ->schema([
                        Toggle::make('is_published')
                            ->default(true)
                            ->label('Published')
                            ->inline(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
            ]);
    }
}
