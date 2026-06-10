<?php

namespace App\Filament\Resources\MarketingAssetResource\Pages;

use App\Filament\Resources\MarketingAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketingAssets extends ListRecords
{
    protected static string $resource = MarketingAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ➕ This adds the "New Branding Asset" button to the top right
            Actions\CreateAction::make()
                ->label('New Asset')
                ->icon('heroicon-m-plus'),
        ];
    }
}