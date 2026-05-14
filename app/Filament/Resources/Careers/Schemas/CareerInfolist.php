<?php

namespace App\Filament\Resources\Careers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CareerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('department'),
                TextEntry::make('location'),
                TextEntry::make('employment_type')->label('Type'),
                TextEntry::make('deadline')
                    ->date()
                    ->placeholder('—'),
                TextEntry::make('is_published')
                    ->badge(),
                TextEntry::make('description')
                    ->html()
                    ->columnSpanFull()
                    ->placeholder('—'),
                TextEntry::make('responsibilities')
                    ->html()
                    ->columnSpanFull()
                    ->placeholder('—'),
                TextEntry::make('qualifications')
                    ->html()
                    ->columnSpanFull()
                    ->placeholder('—'),
                TextEntry::make('submission_guidelines')
                    ->html()
                    ->columnSpanFull()
                    ->placeholder('—'),
            ]);
    }
}
