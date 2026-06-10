<?php
namespace App\Filament\Resources\MarketingAssetResource\Pages;

use App\Filament\Resources\MarketingAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarketingAsset extends EditRecord 
{
    protected static string $resource = MarketingAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}