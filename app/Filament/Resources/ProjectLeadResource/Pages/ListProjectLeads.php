<?php

namespace App\Filament\Resources\ProjectLeadResource\Pages;

use App\Filament\Resources\ProjectLeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectLeads extends ListRecords
{
    protected static string $resource = ProjectLeadResource::class;

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
{
    $query = parent::getTableQuery();

    // If the user is NOT an admin, only show their own leads
    if (auth()->user()->role !== 'admin') {
        $query->where('user_id', auth()->id());
    }

    return $query;
}
}
