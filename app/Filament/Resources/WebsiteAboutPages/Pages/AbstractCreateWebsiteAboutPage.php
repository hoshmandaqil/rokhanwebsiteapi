<?php

namespace App\Filament\Resources\WebsiteAboutPages\Pages;

use App\Enums\AboutType;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

abstract class AbstractCreateWebsiteAboutPage extends CreateRecord
{
    use Translatable;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $resource = static::getResource();

        $data['page_key'] = $resource::pageKey();
        $data['type'] = AboutType::University->value;
        $data['title'] = $resource::defaultTitleForLocales();

        return parent::mutateFormDataBeforeCreate($data);
    }

    public function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
