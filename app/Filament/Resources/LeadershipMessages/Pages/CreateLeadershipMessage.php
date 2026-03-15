<?php

namespace App\Filament\Resources\LeadershipMessages\Pages;

use App\Filament\Resources\LeadershipMessages\LeadershipMessageResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateLeadershipMessage extends CreateRecord
{
    use Translatable;

    protected static string $resource = LeadershipMessageResource::class;

    public function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
