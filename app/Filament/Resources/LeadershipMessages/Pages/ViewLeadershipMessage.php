<?php

namespace App\Filament\Resources\LeadershipMessages\Pages;

use App\Filament\Resources\LeadershipMessages\LeadershipMessageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLeadershipMessage extends ViewRecord
{
    protected static string $resource = LeadershipMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
