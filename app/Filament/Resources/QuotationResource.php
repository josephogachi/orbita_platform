<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuotationResource\Pages;
use App\Models\Quotation;
use App\Models\Product;
use App\Mail\QuotationMail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Sales Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Client Details')
                            ->schema([
                                Forms\Components\TextInput::make('quotation_number')
                                    ->default('QT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)))
                                    ->readonly()
                                    ->required(),
                                Forms\Components\TextInput::make('client_name')
                                    ->required(),
                                Forms\Components\TextInput::make('client_email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('hotel_name'),
                                Forms\Components\TextInput::make('client_phone')
                                    ->tel(),
                            ])->columns(2),

                        Forms\Components\Section::make('Inventory Items')
                            ->description('Select products from your shop categories')
                            ->schema([
                                Forms\Components\Repeater::make('items')
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->label('Select Product')
                                            ->options(Product::all()->pluck('name', 'id'))
                                            ->searchable()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                $product = Product::find($state);
                                                if ($product) {
                                                    $set('description', $product->name);
                                                    $set('price', $product->price);
                                                }
                                            })
                                            ->columnSpan(2),
                                        
                                        Forms\Components\TextInput::make('description')
                                            ->required()
                                            ->hiddenLabel()
                                            ->placeholder('Item Description')
                                            ->columnSpan(2),

                                        Forms\Components\TextInput::make('quantity')
                                            ->numeric()
                                            ->default(1)
                                            ->required()
                                            ->live(),

                                        Forms\Components\TextInput::make('price')
                                            ->numeric()
                                            ->prefix('KES')
                                            ->required()
                                            ->live(),
                                    ])
                                    ->columns(4)
                                    ->reorderable()
                                    ->cloneable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['description'] ?? 'New Item')
                                    ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::updateTotals($set, $get)),
                            ]),

                        Forms\Components\Section::make('Additional Expenses & Services')
                            ->schema([
                                Forms\Components\TextInput::make('installation_fee')
                                    ->label('Installation Fee')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->default(0)
                                    ->live()
                                    ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::updateTotals($set, $get)),

                                Forms\Components\TextInput::make('shipping_fee')
                                    ->label('Shipping & Handling')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->default(0)
                                    ->live()
                                    ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::updateTotals($set, $get)),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Toggle::make('has_maintenance')
                                            ->label('Include Maintenance Subscription')
                                            ->reactive()
                                            ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::updateTotals($set, $get)),

                                        Forms\Components\TextInput::make('maintenance_fee')
                                            ->label('Maintenance Amount')
                                            ->numeric()
                                            ->prefix('KES')
                                            ->default(0)
                                            ->visible(fn ($get) => $get('has_maintenance'))
                                            ->live()
                                            ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::updateTotals($set, $get)),
                                    ]),
                            ]),
                    ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status & Final Totals')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'pending' => 'Pending Review',
                                        'reviewed' => 'Reviewed',
                                        'sent' => 'Sent to Client',
                                        'expired' => 'Expired',
                                    ])
                                    ->default('pending')
                                    ->required()
                                    ->native(false),

                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Products Subtotal')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->readonly(),

                                Forms\Components\TextInput::make('total')
                                    ->label('Grand Total')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->readonly()
                                    ->extraInputAttributes(['class' => 'text-xl font-bold text-orbita-blue']),
                            ]),

                        Forms\Components\Section::make('Internal Notes')
                            ->schema([
                                Forms\Components\Textarea::make('notes')
                                    ->placeholder('Add validity terms or discounts...')
                                    ->rows(4),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    // 🟢 Dynamic Calculation Logic
    public static function updateTotals(Forms\Set $set, Forms\Get $get): void
    {
        $items = collect($get('items') ?? []);
        $subtotal = $items->reduce(fn ($carry, $item) => $carry + ((float)($item['quantity'] ?? 0) * (float)($item['price'] ?? 0)), 0);
        
        $installation = (float)($get('installation_fee') ?? 0);
        $shipping = (float)($get('shipping_fee') ?? 0);
        $maintenance = $get('has_maintenance') ? (float)($get('maintenance_fee') ?? 0) : 0;

        $grandTotal = $subtotal + $installation + $shipping + $maintenance;

        $set('subtotal', $subtotal);
        $set('total', $grandTotal);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quotation_number')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono'),
                
                Tables\Columns\TextColumn::make('client_name')
                    ->searchable()
                    ->description(fn (Quotation $record): string => $record->hotel_name ?? ''),

                Tables\Columns\TextColumn::make('total')
                    ->money('KES')
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'reviewed',
                        'success' => 'sent',
                        'danger' => 'expired',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Date')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('send_email')
                    ->label('Send')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Quotation $record) {
                        try {
                            Mail::to($record->client_email)->send(new QuotationMail($record));
                            $record->update(['status' => 'sent']);
                            Notification::make()->title('Sent Successfully')->success()->send();
                        } catch (\Exception $e) {
                            Notification::make()->title('Mail Error')->danger()->send();
                        }
                    }),

                Tables\Actions\Action::make('download_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function (Quotation $record) {
                        $pdf = Pdf::loadView('pdf.quotation', ['quotation' => $record])
                            ->setPaper('a4', 'portrait');
                        return response()->streamDownload(fn () => print($pdf->output()), "QT-{$record->quotation_number}.pdf");
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }
}