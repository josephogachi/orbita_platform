<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobApplicationResource\Pages;
use App\Models\JobApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection; 

class JobApplicationResource extends Resource
{
    protected static ?string $model = JobApplication::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'HR & Recruitment';
    protected static ?string $navigationLabel = 'Applicants (CVs)';

    // We disable the "Create" button because candidates apply from the frontend
    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('jobPosting.title')
                    ->label('Applied For')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('first_name')
                    ->label('Candidate Name')
                    ->formatStateUsing(fn ($record) => $record->first_name . ' ' . $record->last_name)
                    ->searchable(['first_name', 'last_name']),

                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->icon('heroicon-m-phone')
                    ->copyable()
                    ->searchable(),

                // 🌟 UPDATED: Colored Badges for Statuses
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',    // Yellow/Orange
                        'reviewed' => 'info',      // Blue
                        'shortlisted' => 'success',// Green
                        'rejected' => 'danger',    // Red
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending Review',
                        'reviewed' => 'Reviewed',
                        'shortlisted' => 'Shortlisted',
                        'rejected' => 'Rejected',
                        default => ucfirst($state),
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Applied On')
                    ->date('M d, Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                // 🌟 NEW: The Filter Tool
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter by Status')
                    ->options([
                        'pending' => 'Pending Review',
                        'reviewed' => 'Reviewed',
                        'shortlisted' => 'Shortlisted',
                        'rejected' => 'Rejected',
                    ])
                    ->multiple(), // Allows HR to filter by multiple statuses at once
            ])
            ->actions([
                // 🌟 NEW: Quick Status Changer
                Tables\Actions\Action::make('change_status')
                    ->label('Set Status')
                    ->icon('heroicon-o-tag')
                    ->color('gray')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Update Candidate Status')
                            ->options([
                                'pending' => 'Pending Review',
                                'reviewed' => 'Reviewed',
                                'shortlisted' => 'Shortlisted',
                                'rejected' => 'Rejected',
                            ])
                            ->default(fn (JobApplication $record) => $record->status)
                            ->required(),
                    ])
                    ->action(function (JobApplication $record, array $data) {
                        $record->update(['status' => $data['status']]);
                    }),

                // Securely download their CV/Resume
                Tables\Actions\Action::make('download_cv')
                    ->label('Get CV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->action(function (JobApplication $record) {
                        if (Storage::disk('public')->exists($record->resume_path)) {
                            return Storage::disk('public')->download($record->resume_path, $record->first_name . '_' . $record->last_name . '_CV.' . pathinfo($record->resume_path, PATHINFO_EXTENSION));
                        }
                    }),
                    
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                    // The Extractor Tool
                    Tables\Actions\BulkAction::make('extract_applicants')
                        ->label('Extract Contact Info')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('success')
                        ->modalHeading('Extracted Applicant Data')
                        ->modalDescription('Copy the comma-separated lists below to easily email or SMS these applicants.')
                        ->modalSubmitAction(false) // Removes the submit button since this is read-only
                        ->modalCancelActionLabel('Close')
                        ->action(fn() => null) // Required to prevent database execution errors
                        ->form(function (Collection $records) {
                            $emails = $records->pluck('email')->filter()->implode(', ');
                            $phones = $records->pluck('phone')->filter()->implode(', ');
                            
                            return [
                                Forms\Components\Textarea::make('emails_list')
                                    ->label('Emails')
                                    ->default($emails)
                                    ->rows(4)
                                    ->extraAttributes(['readonly' => true]),
                                    
                                Forms\Components\Textarea::make('phones_list')
                                    ->label('Phone Numbers')
                                    ->default($phones)
                                    ->rows(4)
                                    ->extraAttributes(['readonly' => true]),
                            ];
                        }),

                    // The Standard Bulk Delete
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('portfolio_url')
                    ->label('Portfolio / LinkedIn URL')
                    ->url(),
                Forms\Components\Textarea::make('cover_letter')
                    ->label('Cover Letter / Message')
                    ->columnSpanFull(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobApplications::route('/'),
            'view' => Pages\ViewJobApplication::route('/{record}'),
        ];
    }
}