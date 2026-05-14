<?php

namespace App\Filament\Resources\WebsiteContentPages\Schemas;

use App\Enums\WebsiteContentPageKey;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WebsiteContentPageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('page_key')
                    ->label('Page')
                    ->formatStateUsing(fn (?string $state): string => $state ? (WebsiteContentPageKey::tryFrom($state)?->label() ?? $state) : '—'),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->html()
                    ->columnSpanFull(),
                ImageEntry::make('image')
                    ->columnSpanFull()
                    ->placeholder('—'),
            ]);
    }
}
