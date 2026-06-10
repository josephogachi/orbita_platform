<?php

namespace App\Filament\Resources\MarketingContactResource\Pages;

use App\Filament\Resources\MarketingContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMarketingContacts extends ListRecords
{
    protected static string $resource = MarketingContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // 🎯 THIS CREATES THE PROFESSIONAL TABS AT THE TOP OF THE PAGE
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Hotels')
                ->icon('heroicon-m-building-office-2'),
                
            'nairobi' => Tab::make('Nairobi Region')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('region', 'Nairobi')),
                
            'mombasa' => Tab::make('Mombasa & Coast')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('region', 'Mombasa')),
                
            'nakuru' => Tab::make('Nakuru & Naivasha')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('region', 'Nakuru')),
                
            'kisumu' => Tab::make('Kisumu & Western')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('region', 'Kisumu')),
                
            'mt_kenya' => Tab::make('Mt. Kenya')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('region', 'Mt. Kenya')),
        ];
    }
}