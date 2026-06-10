<?php

namespace App\Filament\Resources\LogisticsProductResource\Pages;

use App\Filament\Resources\LogisticsProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLogisticsProduct extends EditRecord
{
    protected static string $resource = LogisticsProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
