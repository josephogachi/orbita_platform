<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ShopSetting;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Radio;

class PosPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = OrderResource::class;
    protected static ?string $title = 'POS Terminal';
    protected static string $view = 'filament.resources.order-resource.pages.pos-page';

    // Cart & Totals
    public $cart = [];
    public $total = 0;
    public $subtotal = 0;
    public $vat = 0;
    public $vat_rate = 16; // Default
    
    // Filters
    public $search = '';
    public $selectedCategory = null;
    public $selectedBrand = null;
    
    // Checkout
    public $customer_id = null;
    public $payment_method = 'cash';

    public function mount()
    {
        $this->form->fill();
        
        // Load VAT from settings
        $settings = ShopSetting::first();
        if ($settings) {
            $this->vat_rate = $settings->vat_percentage;
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('customer_id')
                    ->label('Customer')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->placeholder('Walk-in Customer (Guest)'),
                
                Radio::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'mpesa' => 'M-Pesa',
                        'card' => 'Card',
                        'bank_transfer' => 'Bank Transfer',
                    ])
                    ->default('cash')
                    ->inline()
            ]);
    }

    // Reset filters
    public function resetFilters()
    {
        $this->search = '';
        $this->selectedCategory = null;
        $this->selectedBrand = null;
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product || $product->stock_quantity < 1) {
            Notification::make()->title('Out of Stock')->danger()->send();
            return;
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'cost' => $product->cost_price ?? 0,
                'image' => $product->images[0] ?? null,
                'quantity' => 1,
            ];
        }
        $this->calculateTotal();
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        $this->calculateTotal();
    }

    public function updateQuantity($productId, $change)
    {
        if (!isset($this->cart[$productId])) return;
        
        $newQty = $this->cart[$productId]['quantity'] + $change;

        if ($newQty < 1) {
            $this->removeFromCart($productId);
        } else {
            $this->cart[$productId]['quantity'] = $newQty;
        }
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->subtotal = array_reduce($this->cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        // DYNAMIC VAT CALCULATION
        $this->vat = $this->subtotal * ($this->vat_rate / 100); 
        $this->total = $this->subtotal + $this->vat;
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            Notification::make()->title('Cart is empty')->warning()->send();
            return;
        }

        $order = DB::transaction(function () {
            $order = Order::create([
                'order_number' => 'POS-' . strtoupper(uniqid()),
                'user_id' => $this->customer_id,
                'status' => 'delivered',
                'payment_status' => 'paid',
                'payment_method' => $this->payment_method,
                'sub_total' => $this->subtotal,
                'vat' => $this->vat,
                'grand_total' => $this->total,
                'currency' => 'KES'
            ]);

            foreach ($this->cart as $item) {
                $order->items()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);
                Product::where('id', $item['id'])->decrement('stock_quantity', $item['quantity']);
            }
            return $order;
        });

        $this->cart = [];
        $this->total = 0;
        $this->subtotal = 0;
        $this->vat = 0;
        $this->form->fill();
        
        Notification::make()->title('Sale Completed')->success()->send();

        return redirect()->route('print.receipt', ['order' => $order->id]);
    }
}