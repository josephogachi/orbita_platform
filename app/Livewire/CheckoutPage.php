<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingZone;
use App\Models\ShopSetting;
use App\Services\MpesaService;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Darryldecode\Cart\Facades\CartFacade as Cart;

#[Title('Checkout')]
class CheckoutPage extends Component
{
    // --- Form Inputs ---
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $address;
    public $notes;
    
    // --- Cart & Costs ---
    public $cartItems = [];
    public $selected_zone_id;
    public $subtotal = 0;
    public $vat_percent = 16;
    public $vat_amount = 0;
    public $shipping_cost = 0;
    public $grand_total = 0;

    /**
     * Initialize the component.
     */
    public function mount()
    {
        // 1. Get Shop Settings (for VAT)
        $settings = ShopSetting::first();
        $this->vat_percent = $settings->vat_percentage ?? 16;

        // 2. Load Items using the Darryldecode Cart Facade
        $this->cartItems = Cart::getContent()->toArray();

        // 3. Check if empty - prevents access to checkout with no items
        if (empty($this->cartItems)) {
            Log::info('Empty cart redirect triggered for user: ' . (auth()->id() ?? 'Guest'));
            return redirect('/'); 
        }

        // 4. Pre-fill user details if logged in
        if (auth()->check()) {
            $user = auth()->user();
            // Splits name if stored as full name, or just uses the whole string
            $nameParts = explode(' ', $user->name, 2);
            $this->first_name = $nameParts[0] ?? '';
            $this->last_name = $nameParts[1] ?? '';
            $this->email = $user->email;
        }

        $this->calculateTotals();
    }

    /**
     * Listen for changes in the shipping zone selection.
     */
    public function updatedSelectedZoneId($value)
    {
        if (!$value) {
            $this->shipping_cost = 0;
            $this->calculateTotals();
            return;
        }

        $zone = ShippingZone::with('rates')->find($value);
        
        if ($zone) {
            // Calculate total weight of cart using the item data
            $totalWeight = collect($this->cartItems)->sum(function($item) {
                // Darryldecode stores custom fields (like weight) in the 'attributes' array
                $weight = $item['attributes']['weight'] ?? 1;
                return $weight * $item['quantity'];
            });

            // Find matching rate based on weight brackets
            $rate = $zone->rates()
                ->where('weight_min', '<=', $totalWeight)
                ->where(function($q) use ($totalWeight) {
                    $q->where('weight_max', '>=', $totalWeight)
                      ->orWhereNull('weight_max');
                })
                ->first();

            $this->shipping_cost = $rate ? $rate->cost : 0;
        }

        $this->calculateTotals();
    }

    /**
     * Calculate all financial totals using Cart Facade for the subtotal.
     */
    public function calculateTotals()
    {
        // Use Darryldecode's built-in subtotal calculation for accuracy
        $this->subtotal = Cart::getSubTotal();
        $this->vat_amount = $this->subtotal * ($this->vat_percent / 100);
        $this->grand_total = $this->subtotal + $this->vat_amount + $this->shipping_cost;
    }

    /**
     * Process the order and trigger M-Pesa STK Push.
     */
    public function placeOrder(MpesaService $mpesaService)
    {
        // 1. Validate Form
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => ['required', 'regex:/^(07|01|254)[0-9]{8}$/'],
            'address' => 'required|string',
            'selected_zone_id' => 'required',
        ]);

        $formattedPhone = $this->formatPhoneNumber($this->phone);

        try {
            // 2. Create the Order in Database
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'status' => 'new',
                'payment_status' => 'unpaid',
                'payment_method' => 'M-Pesa',
                'sub_total' => $this->subtotal,
                'shipping_cost' => $this->shipping_cost,
                'vat' => $this->vat_amount,
                'grand_total' => $this->grand_total,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $formattedPhone,
                'address' => $this->address,
                'shipping_zone_id' => $this->selected_zone_id,
                'notes' => $this->notes,
            ]);

            // 3. Save Order Items
            foreach ($this->cartItems as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['id'],
                    'unit_price'   => $item['price'], 
                    'quantity'     => $item['quantity'],
                    'total_price'  => $item['price'] * $item['quantity'],
                ]);
            }

            // 4. Trigger STK Push via Daraja API
            $response = $mpesaService->initiateStkPush(
                $formattedPhone, 
                $this->grand_total, 
                $order->order_number
            );

            if (isset($response['ResponseCode']) && $response['ResponseCode'] == "0") {
                // 5. Clear the Cart immediately so user can't double-order
                Cart::clear();
                
                // Success: Redirect to wait for PIN entry
                return redirect()->route('payment.processing', ['order' => $order->id]);
            } else {
                session()->flash('error', 'M-Pesa Error: ' . ($response['errorMessage'] ?? 'Unexpected response from Safaricom.'));
            }

        } catch (\Exception $e) {
            Log::error('Checkout Process Error: ' . $e->getMessage());
            session()->flash('error', 'System Error: We could not process your request at this time.');
        }
    }

    /**
     * Helper to format phone for Safaricom (07... to 2547...)
     */
    public function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            return '254' . substr($phone, 1);
        }
        return $phone;
    }

    public function render()
    {
        return view('livewire.checkout-page', [
            'zones' => ShippingZone::where('is_active', true)->get(),
        ]);
    }
}