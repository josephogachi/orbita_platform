<?php

namespace App\Filament\Resources\SideAdResource\Pages;

use App\Filament\Resources\SideAdResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSideAds extends ListRecords
{
    protected static string $resource = SideAdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
