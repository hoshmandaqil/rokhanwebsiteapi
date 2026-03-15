<?php

namespace App\Filament\Resources\Missions\Tables;

use App\Enums\AboutType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->formatStateUsing(fn (AboutType $state): string => $state->label())
                    ->badge()
                    ->sortable(),
                TextColumn::make('faculty.title')
                    ->label('Program')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('department.title')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('content')
                    ->formatStateUsing(fn (?string $state): string => Str::limit(strip_tags($state ?? ''), 50))
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
