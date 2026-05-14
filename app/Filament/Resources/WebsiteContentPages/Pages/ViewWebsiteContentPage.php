<?php

namespace App\Filament\Resources\WebsiteContentPages\Pages;

use App\Filament\Resources\WebsiteContentPages\WebsiteContentPageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ViewRecord\Concerns\Translatable;

class ViewWebsiteContentPage extends ViewRecord
{
    use Translatable;

    protected static string $resource = WebsiteContentPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            EditAction::make(),
        ];
    }
}
