<?php

namespace App\Filament\Resources\WebsiteBanners\Pages;

use App\Filament\Resources\WebsiteBanners\WebsiteBannerResource;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteBanners extends ListRecords
{
    protected static string $resource = WebsiteBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
