<?php

namespace App\Filament\Resources\WebsiteAboutPages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WebsiteAboutPageForm
{
    private const DOCUMENT_MAX_SIZE_KB = 5120; // 5 MB

    public static function configure(Schema $schema, string $publicPath, bool $withDocuments = false): Schema
    {
        $components = [
            Placeholder::make('hint')
                ->label('')
                ->content(fn (): string => 'Shown on the website at '.$publicPath.'. Use the language switcher to edit each language.')
                ->columnSpanFull(),
            RichEditor::make('description')
                ->label('Page content')
                ->columnSpanFull()
                ->required()
                ->minLength(10)
                ->maxLength(65000),
            FileUpload::make('image')
                ->label('Banner image')
                ->image()
                ->acceptedFileTypes(['image/*'])
                ->maxSize(2048)
                ->imageEditor()
                ->columnSpanFull()
                ->required(),
        ];

        if ($withDocuments) {
            $components[] = Repeater::make('documents')
                ->label('Strategic plan PDFs')
                ->helperText('Each item needs a title and a PDF. They appear on the website Strategic Plan page.')
                ->schema([
                    TextInput::make('title')
                        ->label('Title')
                        ->required()
                        ->maxLength(255),
                    FileUpload::make('file')
                        ->label('PDF document (max 5 MB)')
                        ->disk('local')
                        ->directory('abouts/documents')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(self::DOCUMENT_MAX_SIZE_KB)
                        ->required()
                        ->downloadable()
                        ->helperText('PDF only. Maximum size: 5 MB.'),
                ])
                ->defaultItems(0)
                ->collapsible()
                ->reorderable()
                ->itemLabel(fn (array $state): ?string => filled($state['title'] ?? null)
                    ? (string) $state['title']
                    : 'PDF document')
                ->columnSpanFull()
                ->formatStateUsing(function ($state): array {
                    if (! is_array($state)) {
                        return [];
                    }

                    return collect($state)
                        ->filter(fn ($row): bool => is_array($row))
                        ->map(fn (array $row): array => [
                            'title' => trim((string) ($row['title'] ?? '')),
                            'file' => $row['file'] ?? null,
                        ])
                        ->values()
                        ->all();
                })
                ->dehydrateStateUsing(function ($state): array {
                    if (! is_array($state)) {
                        return [];
                    }

                    return collect($state)
                        ->filter(fn ($row): bool => is_array($row))
                        ->map(function (array $row): ?array {
                            $title = trim((string) ($row['title'] ?? ''));
                            $file = $row['file'] ?? null;
                            if (is_array($file)) {
                                $file = collect($file)->filter()->first();
                            }
                            $file = is_string($file) ? trim($file) : null;

                            if ($title === '' || blank($file)) {
                                return null;
                            }

                            return [
                                'title' => $title,
                                'file' => $file,
                            ];
                        })
                        ->filter()
                        ->values()
                        ->all();
                });
        }

        return $schema->components($components);
    }
}
