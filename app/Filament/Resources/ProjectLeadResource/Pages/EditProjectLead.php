<?php

namespace App\Filament\Resources\ProjectLeadResource\Pages;

use App\Filament\Resources\ProjectLeadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectLead extends EditRecord
{
    protected static string $resource = ProjectLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
