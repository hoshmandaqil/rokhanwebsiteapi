<?php

namespace App\Filament\Resources\Careers\Pages;

use App\Filament\Resources\Careers\CareerResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateCareer extends CreateRecord
{
    use Translatable;

    protected static string $resource = CareerResource::class;

    public function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
