<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectLeadResource\Pages;
use App\Models\ProjectLead;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ProjectLeadResource extends Resource
{
    protected static ?string $model = ProjectLead::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Sales Team CRM';
    protected static ?string $navigationLabel = 'Active Projects';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Project & Client Info')
                    ->description('Enter the establishment details. Phone number must be unique.')
                    ->schema([
                        Forms\Components\TextInput::make('hotel_name')
                            ->required()
                            ->label('Establishment Name')
                            ->placeholder('e.g., Hilton Nairobi'),
                            
                        Forms\Components\Select::make('facility_type')
                            ->label('Type of Facility')
                            ->options([
                                'hotel' => 'Hotel / Resort',
                                'apartment' => 'Apartment Complex',
                                'school' => 'School / Institution',
                                'residence' => 'Private Residence',
                                'office' => 'Commercial Office',
                            ])
                            ->required()
                            ->default('hotel'),

                        Forms\Components\TextInput::make('client_name')
                            ->required()
                            ->label('Contact Person')
                            ->placeholder('Full Name'),

                        Forms\Components\TextInput::make('client_phone')
                            ->required()
                            ->tel()
                            ->label('Phone Number')
                            ->unique(ignoreRecord: true) 
                            ->validationMessages([
                                'unique' => 'This client phone is already registered in the system.',
                            ]),

                        Forms\Components\TextInput::make('client_email')
                            ->email()
                            ->label('Email Address'),
                            
                        Forms\Components\TextInput::make('number_of_rooms')
                            ->numeric()
                            ->label('Room / Door Count')
                            ->placeholder('e.g., 50'),
                    ])->columns(2),

                Forms\Components\Section::make('Sales Pipeline')
                    ->schema([
                        Forms\Components\Select::make('interested_products')
                            ->multiple()
                            ->options(Product::all()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->label('Interested Products'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending (New Lead)',
                                'contacted' => 'Contacted / Pitching',
                                'survey_scheduled' => 'Site Survey Scheduled',
                                'ongoing' => 'Work in Progress',
                                'completed' => 'Project Completed',
                                'lost' => 'Lost Deal',
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false),
                            
                        Forms\Components\Textarea::make('remarks')
                            ->label('Internal Notes / Remarks')
                            ->rows(3)
                            ->columnSpanFull(),
                            
                        // 🔒 Hidden field to automatically assign the project to the current agent
                        Forms\Components\Hidden::make('user_id')
                            ->default(Auth::id()),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hotel_name')
                    ->label('Establishment')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('client_name')
                    ->label('Contact')
                    ->description(fn($record) => $record->client_phone)
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Assigned Agent')
                    ->badge()
                    ->color('gray')
                    ->visible(fn() => auth()->user()->role === 'admin'), // Only admins see who owns what

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'contacted' => 'info',
                        'survey_scheduled' => 'info',
                        'ongoing' => 'primary',
                        'completed' => 'success',
                        'lost' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date Created')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'contacted' => 'Contacted',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'lost' => 'Lost',
                    ]),
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Filter by Agent')
                    ->visible(fn() => auth()->user()->role === 'admin'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => auth()->user()->role === 'admin'), // Prevent agents from deleting leads
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn() => auth()->user()->role === 'admin'),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectLeads::route('/'),
            'create' => Pages\CreateProjectLead::route('/create'),
            'edit' => Pages\EditProjectLead::route('/{record}/edit'),
        ];
    }
}