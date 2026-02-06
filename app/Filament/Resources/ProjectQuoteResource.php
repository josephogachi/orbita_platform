<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectQuoteResource\Pages;
use App\Models\ProjectQuote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;

class ProjectQuoteResource extends Resource
{
    protected static ?string $model = ProjectQuote::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?string $navigationGroup = 'Project Management';
    protected static ?string $label = 'Quotation Request';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Quotation Details')
                    ->tabs([
                        // TAB 1: CLIENT & PROPERTY
                        Forms\Components\Tabs\Tab::make('Client & Property')
                            ->icon('heroicon-m-user')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Select::make('user_id')
                                        ->relationship('user', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                    Forms\Components\TextInput::make('hotel_name')
                                        ->label('Property/Hotel Name')
                                        ->placeholder('e.g. Hilton Nairobi'),
                                    Forms\Components\Select::make('property_type')
                                        ->options([
                                            'hotel' => 'Hotel / Resort',
                                            'apartment' => 'Serviced Apartment',
                                            'hospital' => 'Hospital',
                                            'school' => 'School / University',
                                            'office' => 'Corporate Office',
                                            'residence' => 'Residence Home',
                                        ])->required(),
                                    Forms\Components\TextInput::make('mobile_number')
                                        ->tel()
                                        ->required(),
                                    Forms\Components\Select::make('location_type')
                                        ->options([
                                            'nairobi' => 'Nairobi Region',
                                            'coast' => 'Coast Region',
                                            'rift' => 'Rift Valley',
                                            'others' => 'Other (Specify Below)',
                                        ])->live(),
                                    Forms\Components\Textarea::make('exact_location')
                                        ->placeholder('Enter full address or GPS coordinates')
                                        ->visible(fn (Get $get) => $get('location_type') === 'others'),
                                ]),
                            ]),

                        // TAB 2: TECHNICAL SPECS
                        Forms\Components\Tabs\Tab::make('Project Specifications')
                            ->icon('heroicon-m-wrench-screwdriver')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Select::make('product_id')
                                        ->relationship('product', 'name')
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                            $product = \App\Models\Product::find($state);
                                            if ($product) {
                                                $set('unit_price', $product->price);
                                                static::updateTotals($set, $get);
                                            }
                                        }),
                                    Forms\Components\TextInput::make('product_quantity')
                                        ->numeric()
                                        ->default(1)
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(fn (Set $set, Get $get) => static::updateTotals($set, $get)),
                                    
                                    Forms\Components\Select::make('door_type')
                                        ->options([
                                            'wood' => 'Wooden Door',
                                            'aluminum' => 'Aluminum Profile',
                                            'steel' => 'Security Steel Door',
                                            'glass' => 'Glass Door',
                                        ])->visible(fn (Get $get) => str_contains(strtolower(\App\Models\Product::find($get('product_id'))?->name ?? ''), 'lock')),
                                    
                                    Forms\Components\FileUpload::make('door_image')
                                        ->image()
                                        ->directory('quotes/doors'),
                                    
                                    Forms\Components\Select::make('project_stage')
                                        ->options([
                                            'new' => 'New Construction',
                                            'ongoing' => 'Ongoing Renovation',
                                            'replacement' => 'Replacement / Upgrade',
                                        ])->required(),
                                ]),
                            ]),

                        // TAB 3: FINANCIALS
                        Forms\Components\Tabs\Tab::make('Financial Strategy')
                            ->icon('heroicon-m-banknotes')
                            ->schema([
                                Forms\Components\Section::make('Cost Breakdown')->schema([
                                    Forms\Components\Grid::make(3)->schema([
                                        Forms\Components\TextInput::make('unit_price')
                                            ->disabled()
                                            ->prefix('KES'),
                                        Forms\Components\Toggle::make('requires_installation')
                                            ->label('Include Install')
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set, Get $get) => static::updateTotals($set, $get)),
                                        Forms\Components\TextInput::make('installation_fee_per_unit')
                                            ->numeric()
                                            ->default(1500)
                                            ->prefix('KES')
                                            ->visible(fn (Get $get) => $get('requires_installation'))
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set, Get $get) => static::updateTotals($set, $get)),
                                    ]),
                                    
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\Select::make('payment_plan')
                                            ->options([
                                                'one-time' => 'One-Time Payment (100%)',
                                                'installment' => 'Installment Plan (60/40)',
                                            ])->required()
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set, Get $get) => static::updateTotals($set, $get)),
                                        Forms\Components\TextInput::make('deposit_percentage')
                                            ->numeric()
                                            ->default(60)
                                            ->suffix('%')
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set, Get $get) => static::updateTotals($set, $get)),
                                    ]),

                                    Forms\Components\Placeholder::make('estimated_total_display')
                                        ->label('Project Grand Total')
                                        ->content(fn (Get $get) => 'KES ' . number_format($get('estimated_total') ?? 0)),
                                    
                                    Forms\Components\Hidden::make('estimated_total'),
                                ]),
                            ]),
                    ])->columnSpanFull(),
                
                Forms\Components\Section::make('Admin Action')->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending Review',
                            'reviewed' => 'Technically Verified',
                            'drafted' => 'Quotation Drafted',
                            'sent' => 'Sent to Client',
                            'approved' => 'Approved / Contract Signed',
                            'rejected' => 'Rejected',
                        ])->default('pending')->required(),
                ])
            ]);
    }

    // Smart calculation logic for the masterpiece feel
    public static function updateTotals(Set $set, Get $get)
    {
        $unitPrice = (float) $get('unit_price') ?? 0;
        $qty = (int) $get('product_quantity') ?? 0;
        $installFee = $get('requires_installation') ? (float) $get('installation_fee_per_unit') : 0;

        $total = ($unitPrice + $installFee) * $qty;
        $set('estimated_total', $total);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Requested')->date()->sortable(),
                Tables\Columns\TextColumn::make('hotel_name')->label('Property')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('product.name')->label('Product'),
                Tables\Columns\TextColumn::make('product_quantity')->label('Qty')->alignCenter(),
                Tables\Columns\TextColumn::make('estimated_total')->money('KES')->sortable()->color('success'),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'sent' => 'Sent',
                        'approved' => 'Approved',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('whatsapp')
                    ->label('WhatsApp Client')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn ($record) => "https://wa.me/{$record->mobile_number}?text=" . urlencode("Hello {$record->hotel_name}, we have reviewed your quote ORB-Q-{$record->id}...")),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectQuotes::route('/'),
            'create' => Pages\CreateProjectQuote::route('/create'),
            'edit' => Pages\EditProjectQuote::route('/{record}/edit'),
        ];
    }
}