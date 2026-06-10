<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogisticsProductResource\Pages;
use App\Models\LogisticsProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;

class LogisticsProductResource extends Resource
{
    protected static ?string $model = LogisticsProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Logistics & Inventory';
    protected static ?string $navigationLabel = 'CBM & Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Product Identification')->schema([
                    Forms\Components\TextInput::make('product_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('sku')
                        ->label('SKU / Model')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('pcs_per_carton')
                        ->label('Total Pieces in 1 Carton')
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateMetrics($get, $set)),
                ])->columns(3),

                Forms\Components\Section::make('Volume & Weight')->schema([
                    
                    // The Manual Toggle
                    Forms\Components\Toggle::make('is_manual_cbm')
                        ->label('I already know the CBM (Skip Dimensions)')
                        ->onColor('success')
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            if ($state) {
                                // Clear dimensions if they switch to manual
                                $set('carton_length', null);
                                $set('carton_width', null);
                                $set('carton_height', null);
                            }
                            self::calculateMetrics($get, $set);
                        }),

                    // The Dimensions Grid (Hides if toggle is ON)
                    Forms\Components\Grid::make(4)
                        ->hidden(fn (Get $get): bool => $get('is_manual_cbm'))
                        ->schema([
                            Forms\Components\Select::make('dimension_unit')
                                ->options([
                                    'cm' => 'Centimeters (cm)',
                                    'mm' => 'Millimeters (mm)',
                                    'in' => 'Inches (in)'
                                ])
                                ->default('cm')
                                ->live()
                                ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateMetrics($get, $set)),
                                
                            Forms\Components\TextInput::make('carton_length')
                                ->label('Length')
                                ->numeric()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateMetrics($get, $set)),
                                
                            Forms\Components\TextInput::make('carton_width')
                                ->label('Width')
                                ->numeric()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateMetrics($get, $set)),
                                
                            Forms\Components\TextInput::make('carton_height')
                                ->label('Height')
                                ->numeric()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateMetrics($get, $set)),
                    ]),

                    // Weight Grid
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('weight_unit')
                            ->options(['kg' => 'Kilograms (kg)', 'lbs' => 'Pounds (lbs)'])
                            ->default('kg')
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateMetrics($get, $set)),
                            
                        Forms\Components\TextInput::make('carton_gross_weight')
                            ->label('Total Carton Gross Weight')
                            ->numeric()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateMetrics($get, $set)),
                    ]),
                ]),

                Forms\Components\Section::make('Shipping Costs & Final Metrics')->schema([
                    
                    Forms\Components\TextInput::make('shipping_rate_per_cbm')
                        ->label('Freight Rate per 1 CBM (KES)')
                        ->numeric()
                        ->default(57000)
                        ->prefix('KES')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateMetrics($get, $set)),

                    Forms\Components\Grid::make(4)->schema([
                        
                        // If Manual CBM is ON, this field becomes editable. If OFF, it's read-only!
                        Forms\Components\TextInput::make('cbm_per_carton')
                            ->label('CBM (Per Carton)')
                            ->numeric()
                            ->readOnly(fn (Get $get): bool => ! $get('is_manual_cbm'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateMetrics($get, $set))
                            ->extraInputAttributes(fn (Get $get) => $get('is_manual_cbm') ? ['style' => 'border-color: #d4af37; border-width: 2px;'] : []),
                            
                        Forms\Components\TextInput::make('cbm_per_piece')
                            ->label('CBM (Per Piece)')
                            ->readOnly()
                            ->numeric(),
                            
                        Forms\Components\TextInput::make('shipping_cost_per_carton')
                            ->label('Shipping Cost (Full Carton)')
                            ->readOnly()
                            ->prefix('KES')
                            ->numeric(2),
                            
                        Forms\Components\TextInput::make('shipping_cost_per_piece')
                            ->label('Shipping Cost (1 Piece)')
                            ->readOnly()
                            ->prefix('KES')
                            ->numeric(2)
                            ->extraInputAttributes(['style' => 'font-weight: bold; color: #1e3a8a;']),
                    ])
                ]),
            ]);
    }

    public static function calculateMetrics(Get $get, Set $set): void
    {
        $isManual = $get('is_manual_cbm');
        $pcs = (int) $get('pcs_per_carton') ?: 1;
        $rate = (float) $get('shipping_rate_per_cbm') ?: 57000;
        
        $cartonCbm = 0;

        // 1. Determine the Carton CBM based on the toggle
        if ($isManual) {
            $cartonCbm = (float) $get('cbm_per_carton');
        } else {
            $l = (float) $get('carton_length');
            $w = (float) $get('carton_width');
            $h = (float) $get('carton_height');
            $unit = $get('dimension_unit');
            
            if ($l > 0 && $w > 0 && $h > 0) {
                if ($unit === 'cm') {
                    $cartonCbm = ($l * $w * $h) / 1000000;
                } elseif ($unit === 'mm') {
                    $cartonCbm = ($l * $w * $h) / 1000000000;
                } elseif ($unit === 'in') {
                    $cartonCbm = ($l * $w * $h) / 61023.744;
                }
            }
            // Auto-fill the carton CBM if we are not in manual mode
            $set('cbm_per_carton', $cartonCbm > 0 ? number_format($cartonCbm, 6, '.', '') : null);
        }
        
        // 2. Calculate Piece CBM
        $pieceCbm = $cartonCbm / $pcs;
        $set('cbm_per_piece', $cartonCbm > 0 ? number_format($pieceCbm, 6, '.', '') : null);

        // 3. Calculate Financial Costs
        $costCarton = $cartonCbm * $rate;
        $costPiece = $pieceCbm * $rate;
        
        $set('shipping_cost_per_carton', $cartonCbm > 0 ? number_format($costCarton, 2, '.', '') : null);
        $set('shipping_cost_per_piece', $cartonCbm > 0 ? number_format($costPiece, 2, '.', '') : null);
        
        // 4. Calculate Weight per piece
        $totalWeight = (float) $get('carton_gross_weight');
        $weightPerPc = $totalWeight > 0 ? ($totalWeight / $pcs) : 0;
        $set('weight_per_piece', $totalWeight > 0 ? number_format($weightPerPc, 2, '.', '') : null);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_name')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('pcs_per_carton')
                    ->label('Pcs/Box')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('cbm_per_carton')
                    ->label('CBM/Box')
                    ->sortable()
                    ->numeric(4),
                Tables\Columns\TextColumn::make('cbm_per_piece')
                    ->label('CBM/Pc')
                    ->sortable()
                    ->numeric(4)
                    ->color('gray'),
                Tables\Columns\TextColumn::make('shipping_cost_per_carton')
                    ->label('Cost/Box')
                    ->sortable()
                    ->money('KES')
                    ->color('danger'),
                Tables\Columns\TextColumn::make('shipping_cost_per_piece')
                    ->label('Cost/Pc')
                    ->sortable()
                    ->money('KES')
                    ->weight('bold')
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    // Instant CSV Export (Safely handling commas using fputcsv)
                    Tables\Actions\BulkAction::make('export_csv')
                        ->label('Export to CSV')
                        ->icon('heroicon-o-table-cells')
                        ->color('success')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            return response()->streamDownload(function () use ($records) {
                                $file = fopen('php://output', 'w');
                                
                                // Write headers
                                fputcsv($file, ['Product Name', 'SKU', 'Pcs/Carton', 'Carton CBM', 'Piece CBM', 'Carton Weight', 'Piece Weight', 'Cost/Carton (KES)', 'Cost/Piece (KES)']);
                                
                                // Write data rows
                                foreach ($records as $record) {
                                    fputcsv($file, [
                                        $record->product_name,
                                        $record->sku,
                                        $record->pcs_per_carton,
                                        $record->cbm_per_carton,
                                        $record->cbm_per_piece,
                                        $record->carton_gross_weight,
                                        $record->weight_per_piece,
                                        $record->shipping_cost_per_carton,
                                        $record->shipping_cost_per_piece
                                    ]);
                                }
                                fclose($file);
                            }, 'logistics_export_' . date('Y-m-d') . '.csv');
                        }),

                    // Instant PDF Print View
                    Tables\Actions\BulkAction::make('export_pdf')
                        ->label('Export to PDF')
                        ->icon('heroicon-o-document-text')
                        ->color('danger')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            // Store the selected IDs in the session so the print page can load them
                            session()->put('logistics_print_ids', $records->pluck('id')->toArray());
                            return redirect()->to('/logistics/export-pdf');
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLogisticsProducts::route('/'),
            'create' => Pages\CreateLogisticsProduct::route('/create'),
            'edit' => Pages\EditLogisticsProduct::route('/{record}/edit'),
        ];
    }
}