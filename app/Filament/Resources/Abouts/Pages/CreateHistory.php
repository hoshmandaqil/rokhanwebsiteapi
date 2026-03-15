<?php

namespace App\Filament\Resources\Abouts\Pages;

use App\Enums\AboutType;
use App\Filament\Resources\Abouts\HistoryResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateHistory extends CreateRecord
{
    use Translatable;

    protected static string $resource = HistoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = AboutType::History;

        return $data;
    }

    public function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
