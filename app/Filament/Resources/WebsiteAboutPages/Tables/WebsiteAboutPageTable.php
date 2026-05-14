<?php

namespace App\Filament\Resources\WebsiteAboutPages\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WebsiteAboutPageTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Heading')
                    ->limit(50),
                TextColumn::make('updated_at')
                    ->label('Last updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
