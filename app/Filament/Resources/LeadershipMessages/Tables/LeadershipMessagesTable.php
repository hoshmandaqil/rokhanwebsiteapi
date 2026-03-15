<?php

namespace App\Filament\Resources\LeadershipMessages\Tables;

use App\Enums\LeadershipMessageType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class LeadershipMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->formatStateUsing(fn (LeadershipMessageType $state): string => $state->label())
                    ->badge()
                    ->sortable(),
                TextColumn::make('department.title')
                    ->label('Department')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('faculty.title')
                    ->label('Faculty')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
                ImageColumn::make('image')
                    ->circular(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('message')
                    ->formatStateUsing(fn (?string $state): string => Str::limit(strip_tags($state ?? ''), 50))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->sortable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(collect(LeadershipMessageType::cases())->mapWithKeys(fn (LeadershipMessageType $type) => [$type->value => $type->label()])->all()),
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
