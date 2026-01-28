<?php

namespace App\Filament\Pages;

use App\Models\ShopSetting;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;

class ManageShopSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $title = 'Shop Configuration';
    protected static string $view = 'filament.pages.manage-shop-settings';

    public ?array $data = [];

    public function mount(): void
    {
        // Fetch settings or create with expanded defaults
        $settings = ShopSetting::firstOrCreate([], [
            'shop_name' => 'Orbita Solutions',
            'vat_percentage' => 16,
            'bank_name' => 'CO-OPERATIVE BANK',
            'account_name' => 'ORBITAHTECH SYSTEMS KENYA LTD.',
            'account_number' => '01100542859001',
        ]);

        $this->form->fill($settings->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // 1. BRANDING & CONTACT INFO
                Section::make('General Branding & Contact')
                    ->description('Details used for the website header/footer and public profile.')
                    ->schema([
                        TextInput::make('shop_name')
                            ->required()
                            ->label('Company Name'),
                        
                        FileUpload::make('logo_path')
                            ->label('Website Logo')
                            ->image()
                            ->directory('settings')
                            ->columnSpanFull(),

                        Grid::make(2)->schema([
                            TextInput::make('phone_contact')
                                ->label('Support Phone')
                                ->placeholder('+254 700 000 000'),

                            TextInput::make('email_contact')
                                ->label('Support Email')
                                ->email(),
                        ]),
                        
                        TextInput::make('shop_address')
                            ->label('Showroom Address (Main Street)')
                            ->placeholder('G.floor BBS Mall, 12st Eastleigh Nairobi'),

                        TextInput::make('office_address')
                            ->label('Corporate Office Address')
                            ->placeholder('Decale palace hotel 2nd floor 12st'),
                    ])->columns(1),

                // 2. BANKING & TAX (NEW SECTION FOR INVOICES)
                Section::make('Financials & Banking')
                    ->description('These details are used to generate your PDF invoices.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('bank_name')
                                ->label('Bank Name')
                                ->required(),
                            
                            TextInput::make('account_name')
                                ->label('Account Name')
                                ->required(),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('account_number')
                                ->label('Account Number')
                                ->required(),

                            TextInput::make('vat_percentage')
                                ->label('VAT Percentage (%)')
                                ->numeric()
                                ->suffix('%')
                                ->required(),
                        ]),
                    ]),

                // 3. HOMEPAGE COUNTDOWN & PROMOS
                Section::make('Homepage Promotions')
                    ->description('Control the visibility of the top-bar countdown timer.')
                    ->schema([
                        Toggle::make('show_countdown')
                            ->label('Enable Top Bar Countdown')
                            ->default(false)
                            ->live() // Essential to show/hide fields immediately
                            ->columnSpanFull(),

                        TextInput::make('promo_banner_text')
                            ->label('Countdown Text')
                            ->placeholder('FLASH SALE ENDS IN:')
                            ->visible(fn ($get) => $get('show_countdown')),

                        DateTimePicker::make('countdown_end')
                            ->label('Offer Expiry Date')
                            ->native(false)
                            ->visible(fn ($get) => $get('show_countdown')),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $settings = ShopSetting::first();
        $settings->update($this->data);

        Notification::make()
            ->title('Settings Saved Successfully')
            ->success()
            ->send();
    }
}