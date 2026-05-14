<?php

namespace App\Filament\Resources\HomePageSections\Pages;

use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

abstract class AbstractCreateHomePageSection extends CreateRecord
{
    use Translatable;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $resource = static::getResource();
        $data['section_key'] = $resource::sectionKey();

        return parent::mutateFormDataBeforeCreate($data);
    }

    public function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
