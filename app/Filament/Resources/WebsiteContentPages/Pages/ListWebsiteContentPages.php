<?php

namespace App\Filament\Resources\WebsiteContentPages\Pages;

use App\Filament\Resources\WebsiteContentPages\WebsiteContentPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListWebsiteContentPages extends ListRecords
{
    use Translatable;

    protected static string $resource = WebsiteContentPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            LocaleSwitcher::make(),
        ];
    }
}
