<?php

namespace App\Filament\Resources\HomePageSections\Pages;

use App\Models\HomePageSection;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

abstract class AbstractListHomePageSection extends ListRecords
{
    use Translatable;

    public function mount(): void
    {
        parent::mount();

        $resource = static::getResource();
        $record = HomePageSection::query()->where('section_key', $resource::sectionKey())->first();

        if ($record !== null) {
            $this->redirect($resource::getUrl('edit', ['record' => $record]));

            return;
        }

        if ($resource::canCreate()) {
            $this->redirect($resource::getUrl('create'));
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
