<?php
namespace App\Filament\Resources\MarketingListResource\Pages;
use App\Filament\Resources\MarketingListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketingLists extends ListRecords {
    protected static string $resource = MarketingListResource::class;
    protected function getHeaderActions(): array {
        return [Actions\CreateAction::make()->label('New Email List')];
    }
}