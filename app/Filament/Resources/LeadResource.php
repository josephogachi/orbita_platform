<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use App\Models\Order;
use App\Models\ProjectLead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;
    protected static ?string $navigationIcon = 'heroicon-o-funnel';
    protected static ?string $navigationGroup = 'Sales CRM';
    protected static ?string $navigationLabel = 'Lead Pipeline';
    protected static ?string $slug = 'lead-pipeline';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make('Client Details')
                            ->columnSpan(2)
                            ->schema([
                                TextInput::make('company_name')
                                    ->required()
                                    ->placeholder('e.g. Hilton Hotel Nairobi')
                                    ->maxLength(255),
                                
                                Grid::make(3)->schema([
                                    TextInput::make('contact_person')
                                        ->label('Contact Person'),
                                    TextInput::make('contact_position')
                                        ->label('Position / Role')
                                        ->placeholder('e.g. CEO, Procurement Manager')
                                        ->nullable(),
                                    TextInput::make('region')
                                        ->placeholder('e.g. Westlands, Nyali'),
                                ]),

                                Grid::make(2)->schema([
                                    TextInput::make('phone')->tel(),
                                    TextInput::make('email')->email(),
                                ]),

                                RichEditor::make('notes')
                                    ->label('Interaction History & Notes')
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Sales Intelligence')
                            ->columnSpan(1)
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'new' => '🆕 New Lead',
                                        'contacted' => '📞 Contacted',
                                        'meeting' => '📅 Meeting Set',
                                        'proposal' => '📄 Proposal Sent',
                                        'won' => '✅ Closed Won',
                                        'lost' => '❌ Closed Lost',
                                    ])
                                    ->required()
                                    ->default('new')
                                    ->native(false)
                                    ->live(),

                                TextInput::make('estimated_value')
                                    ->label('Deal Value (KES)')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->default(0),

                                DateTimePicker::make('next_follow_up_date')
                                    ->label('Next Follow-up'),

                                Select::make('user_id')
                                    ->label('Assigned Agent')
                                    ->relationship('agent', 'name')
                                    ->default(auth()->id())
                                    ->required()
                                    ->searchable(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_name')
                    ->label('Client')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Lead $record): string => $record->contact_person ?? 'No contact person'),

                TextColumn::make('contact_position')
                    ->label('Position')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('region')
                    ->label('Area')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'gray',
                        'contacted' => 'info',
                        'meeting' => 'warning',
                        'proposal' => 'primary',
                        'won' => 'success',
                        'lost' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),

                TextColumn::make('estimated_value')
                    ->label('Value')
                    ->money('KES')
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Total Pipeline')),

                TextColumn::make('agent.name')
                    ->label('Owner')
                    ->icon('heroicon-m-user')
                    ->sortable(),

                TextColumn::make('next_follow_up_date')
                    ->label('Next Step')
                    ->dateTime('M d, Y')
                    ->color(fn ($state) => $state && $state->isPast() ? 'danger' : 'success')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'new' => 'New Leads',
                        'contacted' => 'Contacted',
                        'won' => 'Won Deals',
                    ]),
                SelectFilter::make('user_id')
                    ->label('By Agent')
                    ->relationship('agent', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('convert_to_order')
                    ->label('Convert to Order')
                    ->icon('heroicon-o-shopping-cart')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Lead $record) {
                        try {
                            $orderNumber = 'ORB-' . strtoupper(Str::random(6));
                            $value = (float) ($record->estimated_value ?? 0);

                            // 1. Create the Order matching your specific SQLite schema
                            $order = Order::create([
                                'order_number'    => $orderNumber,
                                'user_id'         => $record->user_id,
                                'status'          => 'new',            
                                'payment_status'  => 'unpaid',         
                                'currency'        => 'KES',            
                                'sub_total'       => $value,
                                'grand_total'     => $value,
                                'vat'             => 0.00,
                                'discount'        => 0.00,
                                'shipping_amount' => 0.00,             
                                'shipping_cost'   => 0.00,             
                                'payment_method'  => 'Sales CRM Handover',
                                'shipping_address'=> $record->region ?? 'Nairobi',
                                'notes'           => "Lead conversion: " . $record->company_name,
                                'phone'           => $record->phone,
                            ]);

                            // 2. Project Handover
                            if (class_exists(ProjectLead::class)) {
                                ProjectLead::create([
                                    'hotel_name'  => $record->company_name, 
                                    'user_id'     => $record->user_id, 
                                    'status'      => 'not_started',
                                    'description' => "Contact: " . $record->contact_person . "\nNotes: " . strip_tags($record->notes),
                                ]);
                            }

                            Notification::make()
                                ->title('Conversion Successful! 🥂')
                                ->body("Order {$orderNumber} created for " . $record->company_name)
                                ->success()
                                ->send();

                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Conversion Failed')
                                ->body('Schema Error: ' . $e->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    // 📊 EXPORT AS CSV ACTION
                    Tables\Actions\BulkAction::make('export_csv')
                        ->label('Export to CSV')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $callback = function() use ($records) {
                                $file = fopen('php://output', 'w');
                                // CSV Headers
                                fputcsv($file, ['ID', 'Client/Company', 'Contact Person', 'Position', 'Area', 'Phone', 'Email', 'Status', 'Estimated Value', 'Date Created']);
                                
                                // CSV Rows
                                foreach ($records as $record) {
                                    fputcsv($file, [
                                        $record->id,
                                        $record->company_name ?? 'N/A',
                                        $record->contact_person ?? 'N/A',
                                        $record->contact_position ?? 'N/A',
                                        $record->region ?? 'N/A',
                                        $record->phone ?? 'N/A',
                                        $record->email ?? 'N/A',
                                        $record->status ?? 'N/A',
                                        $record->estimated_value ?? 0,
                                        $record->created_at ? $record->created_at->format('Y-m-d') : 'N/A',
                                    ]);
                                }
                                fclose($file);
                            };
                            return response()->streamDownload($callback, 'leads-export-' . date('Ymd') . '.csv');
                        })
                        ->deselectRecordsAfterCompletion(),

                    // 📄 EXPORT AS PDF ACTION
                    Tables\Actions\BulkAction::make('export_pdf')
                        ->label('Export to PDF')
                        ->icon('heroicon-o-document-text')
                        ->color('danger')
                        ->action(function (Collection $records) {
                            $pdf = Pdf::loadView('pdf.leads', ['leads' => $records])
                                      ->setPaper('a4', 'landscape'); // Landscape to fit columns nicely
                                      
                            return response()->streamDownload(fn () => print($pdf->output()), 'leads-export-' . date('Ymd') . '.pdf');
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}