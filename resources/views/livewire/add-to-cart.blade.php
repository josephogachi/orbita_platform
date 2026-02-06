<div class="flex flex-col gap-6 mt-auto">
    {{-- 1. LUXURY SUCCESS NOTIFICATION --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 4000)" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 transform -translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="bg-green-600 text-white text-[10px] font-black uppercase tracking-[0.25em] py-4 px-6 rounded-2xl mb-2 flex justify-between items-center shadow-2xl shadow-green-200/50">
            <span class="flex items-center gap-3">
                <div class="bg-white/20 p-1 rounded-full">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                </div>
                {{ session('success') }}
            </span>
            <button @click="show = false" class="text-white/60 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    {{-- 2. PRIMARY ACTION ROW (QUANTITY & ADD TO CART) --}}
    <div class="flex flex-col sm:flex-row items-stretch gap-4">
        {{-- High-End Quantity Selector --}}
        <div class="flex items-center justify-between border-2 border-orbita-blue/5 bg-[#FBFBFB] rounded-2xl px-6 py-5 sm:w-44 transition-all focus-within:border-orbita-gold focus-within:bg-white shadow-sm">
            <button wire:click="$set('quantity', {{ max(1, $quantity - 1) }})" 
                    class="text-orbita-blue font-black text-2xl hover:text-orbita-gold transition-all active:scale-150 select-none">-</button>
            
            <input type="number" wire:model.live="quantity" 
                   class="w-12 text-center border-none focus:ring-0 font-black text-orbita-blue bg-transparent text-xl appearance-none" readonly>
            
            <button wire:click="$set('quantity', {{ $quantity + 1 }})" 
                    class="text-orbita-blue font-black text-2xl hover:text-orbita-gold transition-all active:scale-150 select-none">+</button>
        </div>
        
        {{-- Unified Masterpiece Add to Cart Button --}}
        <button wire:click="addToCart" 
                wire:loading.attr="disabled"
                class="flex-1 bg-orbita-blue text-white px-8 py-5 rounded-2xl font-black uppercase tracking-[0.25em] text-[11px] hover:bg-orbita-gold hover:shadow-[0_20px_40px_rgba(212,175,55,0.3)] hover:-translate-y-1.5 active:scale-95 transition-all duration-500 flex items-center justify-center gap-4 group disabled:opacity-50 disabled:cursor-not-allowed overflow-hidden relative">
            
            <span wire:loading.remove class="flex items-center gap-3">
                Add To Cart
                <svg class="w-4 h-4 transform group-hover:translate-x-2 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </span>

            <span wire:loading class="flex items-center gap-3">
                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Processing
            </span>
        </button>
    </div>

    {{-- 3. UNIFORM WHATSAPP BUTTON (SYMMETRICAL) --}}
    <a href="https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappMessage }}" 
       target="_blank"
       class="w-full bg-[#25D366] text-white px-8 py-5 rounded-2xl font-black uppercase tracking-[0.25em] text-[11px] hover:bg-[#128C7E] hover:shadow-[0_20px_40px_rgba(37,211,102,0.2)] hover:-translate-y-1.5 active:scale-95 transition-all duration-500 flex items-center justify-center gap-4 shadow-lg">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <span>Secure Order via WhatsApp</span>
    </a>
</div>