<?php
namespace App\Filament\Resources\MarketingListResource\Pages;
use App\Filament\Resources\MarketingListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarketingList extends EditRecord {
    protected static string $resource = MarketingListResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}