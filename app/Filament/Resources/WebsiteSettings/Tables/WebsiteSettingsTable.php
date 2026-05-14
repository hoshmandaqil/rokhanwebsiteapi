<?php

namespace App\Filament\Resources\WebsiteSettings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WebsiteSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('site_name')
                    ->label('Site name')
                    ->searchable(),
                TextColumn::make('contact_email')
                    ->label('Email')
                    ->copyable()
                    ->placeholder('—'),
                TextColumn::make('contact_phone')
                    ->label('Phone')
                    ->placeholder('—'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }
}
