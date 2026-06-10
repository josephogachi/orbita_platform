<?php

namespace App\Filament\Resources\LogisticsProductResource\Pages;

use App\Filament\Resources\LogisticsProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLogisticsProducts extends ListRecords
{
    protected static string $resource = LogisticsProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
