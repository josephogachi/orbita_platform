<div class="flex flex-col lg:flex-row gap-12">
    
    {{-- SIDEBAR FILTER --}}
    <aside class="w-full lg:w-80 flex-shrink-0">
        <div class="sticky top-28 space-y-8">
            <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="font-black text-orbita-blue uppercase text-xs tracking-widest">Filters</h3>
                    <button wire:click="$set('selectedCategories', [])" class="text-[10px] font-bold text-orbita-gold uppercase">Reset</button>
                </div>

                {{-- Category Filter --}}
                <div class="mb-10">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-5">Categories</label>
                    <div class="space-y-3">
                        @foreach($categories as $category)
                        <label class="flex items-center group cursor-pointer">
                            <input type="checkbox" wire:model.live="selectedCategories" value="{{ $category->id }}" class="w-4 h-4 rounded border-gray-300 text-orbita-blue focus:ring-orbita-gold">
                            <span class="ml-3 text-sm font-bold text-gray-600 group-hover:text-orbita-blue transition uppercase tracking-tight">{{ $category->name }}</span>
                            <span class="ml-auto text-[10px] text-gray-300 font-black">{{ $category->products_count }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Price Filter --}}
                <div class="mb-10">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-5">Max Price: KES {{ number_format($maxPrice) }}</label>
                    <input type="range" wire:model.live.debounce.500ms="maxPrice" min="0" max="200000" step="5000" class="w-full h-1.5 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-orbita-gold">
                </div>

                {{-- Stock Toggle --}}
                <div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="inStockOnly" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-orbita-blue after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        <span class="ml-3 text-sm font-bold text-gray-600 uppercase tracking-tight">In Stock Only</span>
                    </label>
                </div>
            </div>
        </div>
    </aside>

    {{-- PRODUCT GRID --}}
    <main class="flex-grow">
        <div class="flex justify-between items-center mb-8 px-4">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Showing {{ $products->total() }} Results</p>
            <select wire:model.live="sort" class="border-none bg-transparent text-sm font-black text-orbita-blue focus:ring-0 uppercase tracking-tight">
                <option value="latest">Latest</option>
                <option value="price_low">Price: Low-High</option>
                <option value="price_high">Price: High-Low</option>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8" wire:loading.class="opacity-50">
            @forelse($products as $product)
                {{-- Reuse your existing classy card here --}}
                <div class="bg-white rounded-[2rem] p-6 shadow-md border border-gray-50">
                    <img src="{{ asset('storage/' . ($product->images[0] ?? '')) }}" class="h-48 w-full object-contain mb-4">
                    <h3 class="text-sm font-black text-orbita-blue uppercase mb-2">{{ $product->name }}</h3>
                    <p class="text-orbita-gold font-black text-lg">KES {{ number_format($product->price) }}</p>
                    <a href="{{ route('product.show', $product->slug) }}" class="mt-4 block text-center py-2 bg-orbita-blue text-white text-[10px] font-black uppercase rounded-lg">View</a>
                </div>
            @empty
                <div class="col-span-full py-20 text-center text-gray-400 uppercase font-black text-xs tracking-widest">No matching products found.</div>
            @endforelse
        </div>

<div class="mt-16">
    {{ $products->links('partials.pagination') }}
</div>    </main>
</div>