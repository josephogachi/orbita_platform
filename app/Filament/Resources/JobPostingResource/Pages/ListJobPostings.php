<?php

namespace App\Filament\Resources\JobPostingResource\Pages;

use App\Filament\Resources\JobPostingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobPostings extends ListRecords
{
    protected static string $resource = JobPostingResource::class;

    // This is the missing piece that creates the "New Job Posting" button!
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}