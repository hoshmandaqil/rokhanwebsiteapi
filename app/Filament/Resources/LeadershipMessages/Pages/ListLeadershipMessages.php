<?php

namespace App\Filament\Resources\LeadershipMessages\Pages;

use App\Filament\Resources\LeadershipMessages\LeadershipMessageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeadershipMessages extends ListRecords
{
    protected static string $resource = LeadershipMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
