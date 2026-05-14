<?php

namespace App\Filament\Resources\WebsiteContentPages\Tables;

use App\Enums\WebsiteContentPageKey;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class WebsiteContentPagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('page_key')
                    ->label('Page')
                    ->formatStateUsing(fn (?string $state): string => $state ? (WebsiteContentPageKey::tryFrom($state)?->label() ?? $state) : '—')
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('description')
                    ->formatStateUsing(fn (?string $state): string => Str::limit(strip_tags($state ?? ''), 70))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
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
