<div class="container py-10 mx-auto px-4 max-w-6xl">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Secure Checkout</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <div class="md:col-span-2 space-y-6">
            
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">1</span>
                    Contact Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input wire:model="first_name" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                        @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input wire:model="last_name" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                        @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input wire:model="email" type="email" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">M-Pesa Phone Number</label>
                        <input wire:model="phone" type="text" placeholder="07XX..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">2</span>
                    Delivery Details
                </h2>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Your Region</label>
                    <select wire:model.live="selected_zone_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border bg-gray-50">
                        <option value="">-- Choose Location --</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}">
                                {{ $zone->name }} ({{ Str::limit(implode(', ', $zone->areas ?? []), 40) }})
                            </option>
                        @endforeach
                    </select>
                    @error('selected_zone_id') <span class="text-red-500 text-xs">Please select a delivery region.</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Specific Address / Building</label>
                    <textarea wire:model="address" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border" placeholder="e.g., Mirage Towers, 2nd Floor"></textarea>
                    @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order Notes (Optional)</label>
                    <textarea wire:model="notes" rows="2" class="w-full border-gray-300 rounded-md shadow-sm p-2 border"></textarea>
                </div>
            </div>
        </div>

        <div class="md:col-span-1">
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 sticky top-8">
                <h2 class="text-lg font-bold mb-4 text-gray-800">Order Summary</h2>

                <div class="space-y-4 mb-6 border-b border-gray-200 pb-6 max-h-64 overflow-y-auto">
                    @foreach($cartItems as $item)
                        <div class="flex gap-3">
                           <div class="w-14 h-14 bg-white rounded border flex items-center justify-center text-gray-300">
    {{-- Notice the change from $item['image'] to $item['attributes']['image'] --}}
    @if(isset($item['attributes']['image']))
        <img src="{{ asset('storage/' . $item['attributes']['image']) }}" class="w-full h-full object-cover rounded">
    @else
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
    @endif
</div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900 line-clamp-1">{{ $item['name'] }}</h3>
                                <div class="flex justify-between mt-1">
                                    <p class="text-xs text-gray-500">x{{ $item['quantity'] }}</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ number_format($item['price'] * $item['quantity']) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-3 text-sm text-gray-600 mb-6">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>{{ number_format($subtotal) }} KES</span>
                    </div>
                    <div class="flex justify-between">
                        <span>VAT ({{ $vat_percent }}%)</span>
                        <span>{{ number_format($vat_amount) }} KES</span>
                    </div>
                    <div class="flex justify-between font-medium text-blue-600">
                        <span>Shipping</span>
                        <span>
                            @if($shipping_cost > 0)
                                {{ number_format($shipping_cost) }} KES
                            @else
                                <span class="text-xs italic text-gray-400">Select Region</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div class="flex justify-between items-end border-t border-gray-200 pt-4 mb-6">
                    <span class="text-base font-bold text-gray-900">Total to Pay</span>
                    <div class="text-right">
                        <span class="block text-2xl font-bold text-blue-700">{{ number_format($grand_total) }}</span>
                        <span class="text-xs text-gray-500">KES</span>
                    </div>
                </div>

                @if (session()->has('error'))
                    <div class="mb-4 p-3 bg-red-50 text-red-700 text-sm rounded border border-red-100 flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif

                <button 
                    wire:click="placeOrder" 
                    wire:loading.attr="disabled"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-lg font-bold text-lg shadow-lg hover:shadow-xl transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    
                    <span wire:loading.remove>Pay with M-Pesa</span>
                    
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
                
                <p class="text-center text-xs text-gray-400 mt-4 flex justify-center items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14h2v2h-2zm0-10h2v8h-2z"/></svg>
                    Transactions are secure and encrypted.
                </p>
            </div>
        </div>
    </div>
</div>