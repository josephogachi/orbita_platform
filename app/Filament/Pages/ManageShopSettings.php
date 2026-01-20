<?php

namespace App\Filament\Pages;

use App\Models\ShopSetting;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
        // Get the first setting row or create it with defaults
        $settings = ShopSetting::firstOrCreate([], [
            'shop_name' => 'Orbita Solutions',
            'vat_percentage' => 16,
        ]);

        $this->form->fill($settings->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // 1. BRANDING & CONTACT INFO
                Section::make('General Branding & Contact')->schema([
                    TextInput::make('shop_name')
                        ->required()
                        ->label('Company Name'),
                    
                    FileUpload::make('logo_path')
                        ->label('Website Logo')
                        ->image()
                        ->directory('settings')
                        ->columnSpanFull(),

                    TextInput::make('phone_contact')
                        ->label('Support Phone')
                        ->placeholder('+254 700 000 000'),

                    TextInput::make('email_contact')
                        ->label('Support Email')
                        ->email(),
                        
                    TextInput::make('shop_address')
                        ->label('Physical Address'),
                ])->columns(2),

                // 2. HOMEPAGE COUNTDOWN & PROMOS
                Section::make('Homepage Promotions')->schema([
                    Toggle::make('show_countdown')
                        ->label('Enable Top Bar Countdown')
                        ->default(false)
                        ->columnSpanFull(),

                    TextInput::make('promo_banner_text')
                        ->label('Countdown Text')
                        ->placeholder('FLASH SALE ENDS IN:')
                        ->hidden(fn (callable $get) => !$get('show_countdown')),

                    DateTimePicker::make('countdown_end')
                        ->label('Offer Expiry Date')
                        ->native(false) // Uses Filament's nice date picker
                        ->hidden(fn (callable $get) => !$get('show_countdown')),
                ])->columns(2),

                // 3. FINANCIAL SETTINGS
                Section::make('Tax & Financials')->schema([
                    TextInput::make('vat_percentage')
                        ->label('VAT Percentage (%)')
                        ->numeric()
                        ->suffix('%')
                        ->required(),
                ])->columns(1)
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $settings = ShopSetting::first();
        $settings->update($this->form->getState());

        Notification::make()->title('Settings Saved Successfully')->success()->send();
    }
}