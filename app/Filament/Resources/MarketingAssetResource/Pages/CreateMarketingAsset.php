<?php
namespace App\Filament\Resources\MarketingAssetResource\Pages;

use App\Filament\Resources\MarketingAssetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMarketingAsset extends CreateRecord 
{
    protected static string $resource = MarketingAssetResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}