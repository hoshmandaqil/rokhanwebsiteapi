<?php

namespace App\Filament\Resources\WebsiteAboutPages\Pages;

use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

abstract class AbstractEditWebsiteAboutPage extends EditRecord
{
    use Translatable;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
