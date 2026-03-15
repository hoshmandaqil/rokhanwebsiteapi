<?php

namespace App\Filament\Resources\Plans\Tables;

use App\Models\Plan;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('poster')
                    ->circular()
                    ->placeholder('—'),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->formatStateUsing(fn ($state) => $state?->label())
                    ->sortable(),
                TextColumn::make('scope')
                    ->formatStateUsing(fn ($state) => $state?->label())
                    ->sortable(),
                TextColumn::make('faculty.title')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('department.title')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('file')
                    ->label('Document')
                    ->formatStateUsing(fn (?string $state, Plan $record): string => filled($record->file) ? 'Yes' : '—')
                    ->placeholder('—'),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn (Plan $record): bool => filled($record->file))
                    ->action(function (Plan $record) {
                        if (! Storage::disk('local')->exists($record->file)) {
                            return;
                        }
                        $title = $record->getTranslation('title', app()->getLocale())
                            ?? $record->getTranslation('title', config('app.fallback_locale', 'en'))
                            ?? $record->slug
                            ?? 'plan';
                        $safeName = preg_replace('/[^\pL\pN\-_]+/u', '-', $title);
                        $safeName = trim($safeName, '-') ?: 'plan';
                        $extension = pathinfo($record->file, PATHINFO_EXTENSION) ?: 'pdf';
                        $downloadName = Str::limit($safeName, 100, '').'.'.$extension;

                        return Storage::disk('local')->download(
                            $record->file,
                            $downloadName,
                            ['Content-Type' => Storage::disk('local')->mimeType($record->file)]
                        );
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
