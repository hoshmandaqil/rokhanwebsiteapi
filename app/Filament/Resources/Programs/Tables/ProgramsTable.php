<?php

namespace App\Filament\Resources\Programs\Tables;

use App\Enums\ProgramLevel;
use App\Models\Program;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProgramsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('level')
                    ->badge()
                    ->formatStateUsing(function (ProgramLevel|string|null $state): string {
                        if ($state instanceof ProgramLevel) {
                            return $state->label();
                        }

                        return is_string($state) && ProgramLevel::tryFrom($state)
                            ? ProgramLevel::from($state)->label()
                            : (string) $state;
                    })
                    ->sortable(),
                TextColumn::make('department_id')
                    ->label('Department')
                    ->formatStateUsing(function ($_, Program $record): string {
                        $dept = $record->department;
                        if (! $dept) {
                            return '—';
                        }
                        $t = $dept->getTranslation('title', app()->getLocale())
                            ?? $dept->getTranslation('title', config('app.fallback_locale', 'en'))
                            ?? '';

                        return is_string($t) && $t !== '' ? $t : '—';
                    }),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published'),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
