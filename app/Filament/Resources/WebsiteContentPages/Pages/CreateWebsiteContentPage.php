<?php

namespace App\Filament\Resources\WebsiteContentPages\Pages;

use App\Filament\Resources\WebsiteContentPages\WebsiteContentPageResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateWebsiteContentPage extends CreateRecord
{
    use Translatable;

    protected static string $resource = WebsiteContentPageResource::class;

    public function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
