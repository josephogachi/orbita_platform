<?php
namespace App\Filament\Resources\MarketingListResource\Pages;
use App\Filament\Resources\MarketingListResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMarketingList extends CreateRecord {
    protected static string $resource = MarketingListResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}