<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProjectQuote;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class ProjectQuoteForm extends Component
{
    use WithFileUploads;

    // Client & Property Info
    public $hotel_name, $property_type, $mobile_number, $location_type, $exact_location;
    
    // Technical Project Details
    public $project_stage, $door_type, $door_image, $requires_installation = false;
    public $payment_plan = 'one-time';

    // Multi-Product Selection
    public $selectedItems = []; 
    
    // Calculation Totals
    public $subtotal = 0, $shippingFee = 0, $installationTotal = 0, $grandTotal = 0, $depositRequired = 0;

    public function mount($product_id = null)
    {
        $this->mobile_number = Auth::user()->phone ?? '';
        if ($product_id) { $this->addItem($product_id); } else { $this->addItem(); }
    }

    public function addItem($id = null)
    {
        $this->selectedItems[] = ['product_id' => $id, 'quantity' => 1, 'price' => $id ? Product::find($id)->price : 0];
        $this->calculateTotals();
    }

    public function removeItem($index) {
        unset($this->selectedItems[$index]);
        $this->selectedItems = array_values($this->selectedItems);
        $this->calculateTotals();
    }

    public function updated($propertyName)
    {
        if (str_contains($propertyName, 'selectedItems')) {
            foreach ($this->selectedItems as $index => $item) {
                if ($item['product_id']) {
                    $this->selectedItems[$index]['price'] = Product::find($item['product_id'])->price;
                }
            }
        }
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        $totalUnits = 0;

        foreach ($this->selectedItems as $item) {
            if ($item['product_id']) {
                $this->subtotal += (float)$item['price'] * (int)$item['quantity'];
                $totalUnits += (int)$item['quantity'];
            }
        }

        // Installation: KES 1,500 per lock/unit
        $this->installationTotal = $this->requires_installation ? ($totalUnits * 1500) : 0;

        // Shipping Rates
        $rates = ['nairobi' => 1000, 'coast' => 5000, 'rift' => 3500, 'others' => 7500];
        $this->shippingFee = $rates[$this->location_type] ?? 0;

        $this->grandTotal = $this->subtotal + $this->installationTotal + $this->shippingFee;
        $this->depositRequired = ($this->payment_plan === 'installment') ? ($this->grandTotal * 0.60) : $this->grandTotal;
    }

    public function submit()
    {
        $this->validate([
            'property_type' => 'required',
            'mobile_number' => 'required',
            'location_type' => 'required',
            'selectedItems.*.product_id' => 'required',
            'door_image' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $imagePath = $this->door_image ? $this->door_image->store('quotes/doors', 'public') : null;

        ProjectQuote::create([
            'user_id' => Auth::id(),
            'hotel_name' => $this->hotel_name,
            'property_type' => $this->property_type,
            'mobile_number' => $this->mobile_number,
            'location_type' => $this->location_type,
            'exact_location' => $this->exact_location,
            'selected_products' => $this->selectedItems, 
            'project_stage' => $this->project_stage,
            'door_type' => $this->door_type,
            'door_image' => $imagePath,
            'requires_installation' => $this->requires_installation,
            'payment_plan' => $this->payment_plan,
            'estimated_total' => $this->grandTotal,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Quotation Request Received!');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.project-quote-form', [
            'products' => Product::where('is_active', true)->orderBy('name')->get()
        ])->layout('layouts.app');
    }
}