<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('pos')
                ->label('Open POS Terminal')
                ->icon('heroicon-o-computer-desktop')
                ->color('success')
                ->url(OrderResource::getUrl('pos')), // This links to our new page
        ];
    }
}