<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketingContactResource\Pages;
use App\Models\MarketingContact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;

class MarketingContactResource extends Resource
{
    protected static ?string $model = MarketingContact::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Hotel Directory';
    protected static ?int $navigationSort = 1;

    // Makes the global search bar look for hotel names by default
    protected static ?string $recordTitleAttribute = 'hotel_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Hotel Location')->schema([
                    Forms\Components\TextInput::make('hotel_name')
                        ->label('Hotel / Property Name')
                        ->required()
                        ->maxLength(255),
                    
                    Forms\Components\TextInput::make('contact_person')
                        ->label('Contact Person (Manager/Owner)')
                        ->maxLength(255),

                    Forms\Components\Select::make('region')
                        ->label('Region')
                        ->options([
                            'Nairobi' => 'Nairobi & Environs',
                            'Mombasa' => 'Mombasa & Coast',
                            'Nakuru' => 'Nakuru & Naivasha',
                            'Kisumu' => 'Kisumu & Western',
                            'Mt. Kenya' => 'Mt. Kenya Region',
                            'Other' => 'Other',
                        ])
                        ->searchable()
                        ->live() 
                        ->required()
                        ->default('Nairobi'),

                    Forms\Components\TextInput::make('area')
                        ->label('Specific Area (e.g., Westlands)')
                        ->datalist(function (Forms\Get $get) {
                            return match ($get('region')) {
                                'Nairobi' => ['Westlands', 'Parklands', 'Kilimani', 'Karen', 'CBD', 'Upper Hill', 'Eastleigh', 'Langata'],
                                'Mombasa' => ['Nyali', 'Diani', 'Bamburi', 'Shanzu', 'Mtwapa', 'Mombasa Island', 'Kikambala'],
                                'Nakuru' => ['Naivasha Town', 'Nakuru CBD', 'Milimani', 'Gilgil'],
                                default => [],
                            };
                        })
                        ->maxLength(255),
                ])->columns(2),

                Forms\Components\Section::make('Contact Details')->schema([
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->label('Email Address')
                        ->maxLength(255),
                    
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->label('Phone Number')
                        ->maxLength(255),
                        
                    Forms\Components\Select::make('status')
                        ->options([
                            'active' => 'Active (Subscribed)',
                            'do_not_contact' => 'Do Not Contact',
                        ])
                        ->default('active')
                        ->required(),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hotel_name')
                    ->label('Hotel')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('area')
                    ->label('Neighborhood / Area')
                    ->searchable()
                    ->sortable()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('contact_person')
                    ->label('Contact Person')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Region is hidden by default because the Tabs will handle it!
                Tables\Columns\TextColumn::make('region')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage('Email copied')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->icon('heroicon-m-phone')
                    ->copyable()
                    ->copyMessage('Phone copied')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'active' => 'success',
                        'do_not_contact' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('area')
                    ->label('Filter by Area')
                    ->options(fn () => MarketingContact::query()
                        ->whereNotNull('area')
                        ->distinct()
                        ->pluck('area', 'area')
                        ->toArray()
                    )
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                    // 🎯 MASS EXTRACTION TOOL
                    Tables\Actions\BulkAction::make('extract_contacts')
                        ->label('Extract Contacts')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('success')
                        ->modalHeading('Extracted Contact Data')
                        ->modalDescription('Copy the comma-separated lists below for your email/SMS marketing campaigns.')
                        ->modalSubmitAction(false) 
                        ->modalCancelActionLabel('Close')
                        ->action(fn() => null) // Required so it doesn't try to run a database query
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

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketingContacts::route('/'),
            'create' => Pages\CreateMarketingContact::route('/create'),
            'edit' => Pages\EditMarketingContact::route('/{record}/edit'),
        ];
    }
}