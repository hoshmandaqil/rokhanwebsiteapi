<?php

namespace App\Filament\Resources\Activities\Pages;

use App\Filament\Resources\Activities\ActivityResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateActivity extends CreateRecord
{
    use Translatable;

    protected static string $resource = ActivityResource::class;

    public function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
