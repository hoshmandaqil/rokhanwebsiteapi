<?php

namespace App\Filament\Resources\LeadershipMessages\Pages;

use App\Filament\Resources\LeadershipMessages\LeadershipMessageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditLeadershipMessage extends EditRecord
{
    use Translatable;

    protected static string $resource = LeadershipMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            DeleteAction::make(),
        ];
    }
}
