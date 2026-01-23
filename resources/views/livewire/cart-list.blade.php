<div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-gray-100">
    <div class="flex justify-between items-center mb-10">
        <h2 class="text-2xl font-black text-orbita-blue uppercase tracking-tighter">Shopping Cart</h2>
        <button wire:click="clearCart" class="text-[10px] font-black text-red-500 uppercase tracking-widest hover:underline">Clear All</button>
    </div>

    @if($cartItems->isEmpty())
        <div class="text-center py-20">
            <p class="text-gray-400 font-bold uppercase text-xs tracking-widest">Your cart is empty</p>
            <a href="{{ route('products.index') }}" class="mt-4 inline-block text-orbita-gold font-black uppercase text-[10px] tracking-widest">Back to Shop</a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($cartItems as $item)
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 p-6 bg-orbita-light rounded-2xl border border-gray-50">
                    <div class="flex items-center gap-6 flex-1">
                        <img src="{{ asset('storage/' . $item->attributes->image) }}" class="w-20 h-20 object-contain bg-white rounded-xl p-2 shadow-sm">
                        <div>
                            <h4 class="font-black text-orbita-blue uppercase text-sm tracking-tight">{{ $item->name }}</h4>
                            <p class="text-orbita-gold font-bold text-xs">KES {{ number_format($item->price) }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center bg-white rounded-lg border border-gray-200">
                            <button wire:click="updateQuantity('{{ $item->id }}', {{ $item->quantity - 1 }})" class="px-3 py-1 text-orbita-blue font-bold">-</button>
                            <span class="px-4 py-1 font-black text-xs text-orbita-blue border-x border-gray-100">{{ $item->quantity }}</span>
                            <button wire:click="updateQuantity('{{ $item->id }}', {{ $item->quantity + 1 }})" class="px-3 py-1 text-orbita-blue font-bold">+</button>
                        </div>
                        <button wire:click="removeItem('{{ $item->id }}')" class="text-red-400 hover:text-red-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>

                    <div class="text-right min-w-[120px]">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Subtotal</p>
                        <p class="text-lg font-black text-orbita-blue">KES {{ number_format($item->getPriceSum()) }}</p>
                    </div>
                </div>
            @endforeach

            <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col items-end gap-6">
                <div class="text-right">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Grand Total</p>
                    <p class="text-4xl font-black text-orbita-blue tracking-tighter">KES {{ number_format($total) }}</p>
                </div>
                <a href="/checkout" class="w-full md:w-auto bg-orbita-blue text-white px-12 py-5 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-orbita-gold transition shadow-xl text-center">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    @endif
</div>