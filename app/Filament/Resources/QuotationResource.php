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
use Illuminate\Support\Str;

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
                                    
                                // 🌟 FIX 1: Email is explicitly optional now
                                Forms\Components\TextInput::make('client_email')
                                    ->email()
                                    ->required(false)
                                    ->label('Client Email (Optional)'), 
                                    
                                Forms\Components\TextInput::make('hotel_name'),
                                Forms\Components\TextInput::make('client_phone')
                                    ->label('WhatsApp Number')
                                    ->placeholder('e.g. 0712345678')
                                    ->tel(),
                            ])->columns(2),

                        Forms\Components\Section::make('Inventory Items')
                            ->description('Select a product to auto-fill, OR skip the dropdown and type a custom item below.')
                            ->schema([
                                Forms\Components\Repeater::make('items')
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->label('System Product (Optional)')
                                            ->placeholder('Select to auto-fill...')
                                            ->options(Product::all()->pluck('name', 'id'))
                                            ->searchable()
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                $product = Product::find($state);
                                                if ($product) {
                                                    $set('description', $product->name);
                                                    $set('price', $product->price);
                                                }
                                            })
                                            ->columnSpan(4),
                                        
                                        Forms\Components\TextInput::make('description')
                                            ->label('Item Name / Description')
                                            ->required()
                                            ->placeholder('Type custom item name here...')
                                            ->columnSpan(2),

                                        Forms\Components\TextInput::make('quantity')
                                            ->numeric()
                                            ->default(1)
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::updateTotals($set, $get))
                                            ->columnSpan(1),

                                        Forms\Components\TextInput::make('price')
                                            ->label('Unit Price')
                                            ->numeric()
                                            ->prefix('KES')
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::updateTotals($set, $get))
                                            ->columnSpan(1),
                                    ])
                                    ->columns(4)
                                    ->reorderable()
                                    ->cloneable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['description'] ?? 'New Item')
                                    ->deleteAction(fn (Forms\Components\Actions\Action $action) => $action->after(fn(Forms\Set $set, Forms\Get $get) => self::updateTotals($set, $get)))
                            ]),

                        Forms\Components\Section::make('Additional Expenses & Services')
                            ->schema([
                                Forms\Components\TextInput::make('installation_fee')
                                    ->label('Installation Fee')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->default(0)
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::updateTotals($set, $get)),

                                Forms\Components\TextInput::make('shipping_fee')
                                    ->label('Shipping & Handling')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->default(0)
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::updateTotals($set, $get)),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Toggle::make('has_maintenance')
                                            ->label('Include Maintenance Subscription')
                                            ->live()
                                            ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::updateTotals($set, $get)),

                                        Forms\Components\TextInput::make('maintenance_fee')
                                            ->label('Maintenance Amount')
                                            ->numeric()
                                            ->prefix('KES')
                                            ->default(0)
                                            ->visible(fn ($get) => $get('has_maintenance'))
                                            ->live(debounce: 500)
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

                                // 🌟 FIX 2: VAT is fully optional and defaults to OFF
                                Forms\Components\Toggle::make('is_vat_inclusive')
                                    ->label('Apply 16% VAT')
                                    ->onColor('success')
                                    ->default(false) 
                                    ->live()
                                    ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::updateTotals($set, $get)),

                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->readonly(),

                                Forms\Components\TextInput::make('vat_amount')
                                    ->label('VAT (16%)')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->readonly()
                                    ->visible(fn ($get) => $get('is_vat_inclusive')),

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

    public static function updateTotals(Forms\Set $set, Forms\Get $get): void
    {
        $items = collect($get('items') ?? []);
        $itemsSubtotal = $items->reduce(fn ($carry, $item) => $carry + ((float)($item['quantity'] ?? 0) * (float)($item['price'] ?? 0)), 0);
        
        $installation = (float)($get('installation_fee') ?? 0);
        $shipping = (float)($get('shipping_fee') ?? 0);
        $maintenance = $get('has_maintenance') ? (float)($get('maintenance_fee') ?? 0) : 0;

        $subtotal = $itemsSubtotal + $installation + $shipping + $maintenance;
        
        // 🌟 Only applies VAT if the toggle is checked
        $vatAmount = $get('is_vat_inclusive') ? ($subtotal * 0.16) : 0; 
        
        $grandTotal = $subtotal + $vatAmount;

        $set('subtotal', $subtotal);
        $set('vat_amount', $vatAmount);
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

                Tables\Columns\TextColumn::make('status')
                    ->badge()
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

                // 🌟 FIX 3: WhatsApp Action now securely generates the PDF Link
                Tables\Actions\Action::make('share_whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(function (Quotation $record) {
                        $phone = preg_replace('/[^0-9]/', '', $record->client_phone ?? '');
                        if (str_starts_with($phone, '0')) {
                            $phone = '254' . substr($phone, 1);
                        }
                        
                        $amount = number_format($record->total, 2);
                        
                        // Generates a fully qualified public URL (e.g. https://orbitakenya.com/quotations/1/download)
                        $pdfLink = url("/quotations/{$record->id}/download");
                        
                        $message = "Hello {$record->client_name},\n\nHere is your quotation ({$record->quotation_number}) for KES {$amount}.\n\nYou can view and download your official document here: {$pdfLink}\n\nPlease let us know if you have any questions!\n\n- Orbita Kenya";
                        
                        return "https://wa.me/{$phone}?text=" . urlencode($message);
                    })
                    ->openUrlInNewTab()
                    ->visible(fn (Quotation $record): bool => filled($record->client_phone)),

                Tables\Actions\Action::make('send_email')
                    ->label('Email')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Quotation $record): bool => filled($record->client_email)) // Hide if no email
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