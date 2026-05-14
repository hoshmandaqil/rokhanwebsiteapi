<?php

namespace App\Filament\Resources\WebsiteAboutPages\Pages;

use App\Models\About;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

abstract class AbstractListWebsiteAboutPage extends ListRecords
{
    use Translatable;

    public function mount(): void
    {
        parent::mount();

        $resource = static::getResource();
        $pageKey = $resource::pageKey();
        $record = About::query()->where('page_key', $pageKey)->first();

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
