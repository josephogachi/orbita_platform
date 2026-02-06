<?php

namespace App\Filament\Resources\ProjectLeadResource\Pages;

use App\Filament\Resources\ProjectLeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectLeads extends ListRecords
{
    protected static string $resource = ProjectLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
