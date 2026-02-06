<?php
namespace App\Filament\Resources\ProjectQuoteResource\Pages;
use App\Filament\Resources\ProjectQuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectQuote extends EditRecord {
    protected static string $resource = ProjectQuoteResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}