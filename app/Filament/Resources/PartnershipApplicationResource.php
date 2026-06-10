<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnershipApplicationResource\Pages;
use App\Models\PartnershipApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;

class PartnershipApplicationResource extends Resource
{
    protected static ?string $model = PartnershipApplication::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Partnerships';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Application Status')->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'Pending Review' => 'Pending Review',
                            'Interviewing' => 'Interviewing',
                            'Approved' => 'Approved',
                            'Rejected' => 'Rejected',
                        ])->required()->native(false),
                ]),
                Section::make('Company Details')->columns(2)->schema([
                    Forms\Components\TextInput::make('company_name')->disabled(),
                    Forms\Components\TextInput::make('kra_pin')->disabled(),
                    Forms\Components\TextInput::make('business_type')->disabled(),
                    Forms\Components\TextInput::make('years_active')->disabled(),
                ]),
                Section::make('Contact Information')->columns(2)->schema([
                    Forms\Components\TextInput::make('contact_person')->disabled(),
                    Forms\Components\TextInput::make('email')->disabled(),
                    Forms\Components\TextInput::make('phone')->disabled(),
                    Forms\Components\TextInput::make('physical_address')->disabled(),
                ]),
                Section::make('Strategy & Region')->columns(2)->schema([
                    Forms\Components\TextInput::make('region')->disabled(),
                    Forms\Components\TextInput::make('team_size')->disabled(),
                    Forms\Components\Textarea::make('proposal')->columnSpanFull()->disabled(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y')->label('Applied On')->sortable(),
                Tables\Columns\TextColumn::make('company_name')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('region')->badge()->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\SelectColumn::make('status')->options([
                    'Pending Review' => 'Pending Review',
                    'Interviewing' => 'Interviewing',
                    'Approved' => 'Approved',
                    'Rejected' => 'Rejected',
                ])->selectablePlaceholder(false),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([ Tables\Actions\EditAction::make()->label('Review') ])
            ->bulkActions([ Tables\Actions\DeleteBulkAction::make() ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnershipApplications::route('/'),
            'edit' => Pages\EditPartnershipApplication::route('/{record}/edit'),
        ];
    }
}