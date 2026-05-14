<?php

namespace App\Filament\Resources\WebsiteContentPages\Pages;

use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

abstract class AbstractEditWebsiteContentPage extends EditRecord
{
    use Translatable;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
