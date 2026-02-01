<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-8rem)]">
        
        <div class="lg:col-span-2 flex flex-col gap-4 h-full">
            
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Scan Barcode or Search..." 
                           class="w-full p-2 border rounded-lg dark:bg-gray-900 dark:border-gray-600 focus:ring-primary-500"
                           autofocus>
                </div>
                
                <div class="w-full md:w-48">
                    <select wire:model.live="selectedCategory" class="w-full p-2 border rounded-lg dark:bg-gray-900 dark:border-gray-600 focus:ring-primary-500">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Category::where('is_active', true)->pluck('name', 'id') as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-48">
                    <select wire:model.live="selectedBrand" class="w-full p-2 border rounded-lg dark:bg-gray-900 dark:border-gray-600 focus:ring-primary-500">
                        <option value="">All Brands</option>
                        @foreach(\App\Models\Brand::where('is_active', true)->pluck('name', 'id') as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <button wire:click="resetFilters" 
                        class="px-4 py-2 text-sm text-red-600 bg-red-50 hover:bg-red-100 rounded-lg border border-red-200 transition">
                    Reset
                </button>
            </div>

            <div class="grid grid-cols-3 xl:grid-cols-4 gap-4 overflow-y-auto pr-2 pb-20">
                @php
                    $query = \App\Models\Product::where('is_active', true);
                    
                    // Apply Search
                    if($this->search) {
                        $query->where(function($q) {
                            $q->where('name', 'like', '%' . $this->search . '%')
                              ->orWhere('sku', 'like', '%' . $this->search . '%');
                        });
                    }

                    // Apply Category Filter
                    if($this->selectedCategory) {
                        $query->where('category_id', $this->selectedCategory);
                    }

                    // Apply Brand Filter
                    if($this->selectedBrand) {
                        $query->where('brand_id', $this->selectedBrand);
                    }

                    $products = $query->limit(50)->get();
                @endphp

                @foreach($products as $product)
                    <button wire:click="addToCart({{ $product->id }})" 
                            class="flex flex-col items-center bg-white dark:bg-gray-800 rounded-xl shadow p-3 hover:ring-2 ring-primary-500 transition text-left h-full border border-gray-200 dark:border-gray-700">
                        
                        <div class="h-24 w-full bg-gray-100 dark:bg-gray-700 rounded-lg mb-2 overflow-hidden flex items-center justify-center">
                             @if(!empty($product->images) && isset($product->images[0]))
                                <img src="{{ asset('storage/' . $product->images[0]) }}" class="object-cover h-full w-full">
                            @else
                                <span class="text-gray-400 text-xs">No IMG</span>
                            @endif
                        </div>
                        
                        <span class="font-bold text-sm line-clamp-2 w-full dark:text-gray-200">{{ $product->name }}</span>
                        
                        <div class="mt-auto w-full flex justify-between items-center pt-2">
                            <span class="text-primary-600 font-bold">KES {{ number_format($product->price) }}</span>
                            <span class="text-[10px] bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded-full text-gray-600 dark:text-gray-300">
                                {{ $product->stock_quantity }}
                            </span>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-xl flex flex-col h-full border border-gray-200 dark:border-gray-700 sticky top-0">
            
            <div class="p-4 bg-primary-600 text-white rounded-t-xl flex justify-between items-center">
                <h2 class="text-xl font-bold">Sales Terminal</h2>
                <span class="text-sm opacity-80">{{ date('d M Y') }}</span>
            </div>

            <div class="p-4 border-b dark:border-gray-700">
                {{ $this->form }}
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-2">
                @forelse($cart as $id => $item)
                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-800 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                        <div class="flex-1">
                            <div class="font-bold dark:text-gray-200">{{ $item['name'] }}</div>
                            <div class="text-xs text-gray-500">KES {{ number_format($item['price']) }} / unit</div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <div class="flex items-center bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-md">
                                <button wire:click="updateQuantity({{ $id }}, -1)" class="px-3 py-1 hover:bg-gray-100 dark:hover:bg-gray-600 text-lg font-bold">-</button>
                                <span class="px-2 font-mono dark:text-gray-200">{{ $item['quantity'] }}</span>
                                <button wire:click="updateQuantity({{ $id }}, 1)" class="px-3 py-1 hover:bg-gray-100 dark:hover:bg-gray-600 text-lg font-bold">+</button>
                            </div>
                            
                            <div class="font-bold w-16 text-right dark:text-gray-200">
                                {{ number_format($item['price'] * $item['quantity']) }}
                            </div>
                            
                            <button wire:click="removeFromCart({{ $id }})" class="text-red-500 hover:text-red-700">
                                <x-heroicon-o-trash class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="h-40 flex flex-col items-center justify-center text-gray-400 italic">
                        <x-heroicon-o-shopping-cart class="w-12 h-12 mb-2 opacity-50" />
                        <p>Cart is empty.</p> 
                        <p class="text-xs">Scan items to begin.</p>
                    </div>
                @endforelse
            </div>

            <div class="p-6 bg-gray-50 dark:bg-gray-800 border-t dark:border-gray-700 rounded-b-xl space-y-2">
                <div class="flex justify-between text-sm dark:text-gray-400">
                    <span>Subtotal</span>
                    <span>{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm dark:text-gray-400">
                    <span>VAT ({{ $vat_rate }}%)</span>
                    <span>{{ number_format($vat, 2) }}</span>
                </div>
                <div class="flex justify-between text-2xl font-bold text-primary-600 border-t dark:border-gray-600 pt-2 mt-2">
                    <span>TOTAL</span>
                    <span>KES {{ number_format($total, 2) }}</span>
                </div>

                <button wire:click="checkout" 
                        wire:loading.attr="disabled"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl shadow-lg text-lg flex items-center justify-center gap-2 mt-4 transition transform active:scale-95">
                    <x-heroicon-o-printer class="w-6 h-6" />
                    PAY & PRINT RECEIPT
                </button>
            </div>
        </div>

    </div>
</x-filament-panels::page>