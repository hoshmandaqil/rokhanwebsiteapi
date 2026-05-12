<?php

namespace App\Filament\Resources\Programs\Pages;

use App\Filament\Resources\Programs\ProgramResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateProgram extends CreateRecord
{
    use Translatable;

    protected static string $resource = ProgramResource::class;

    public function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
