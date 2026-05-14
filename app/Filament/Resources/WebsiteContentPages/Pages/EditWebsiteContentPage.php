<?php

namespace App\Filament\Resources\WebsiteContentPages\Pages;

use App\Filament\Resources\WebsiteContentPages\WebsiteContentPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditWebsiteContentPage extends EditRecord
{
    use Translatable;

    protected static string $resource = WebsiteContentPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            DeleteAction::make(),
        ];
    }
}
