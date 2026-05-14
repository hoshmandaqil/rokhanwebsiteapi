<?php

namespace App\Filament\Resources\HomePageSections\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HomePageSectionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('updated_at')
                    ->label('Last updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
