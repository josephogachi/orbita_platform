<div class="bg-orbita-light min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <aside class="w-full lg:w-1/4 space-y-6">
                <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-gray-100">
                    <h3 class="text-xl font-black text-orbita-blue uppercase tracking-tighter mb-6">Search</h3>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search locks, safes..." 
                           class="w-full bg-orbita-light border-none rounded-xl p-4 text-sm focus:ring-2 focus:ring-orbita-gold">

                    <h3 class="text-xl font-black text-orbita-blue uppercase tracking-tighter mt-10 mb-6">Categories</h3>
                    <div class="space-y-2">
                        <button wire:click="$set('selectedCategory', null)" 
                                class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition {{ is_null($selectedCategory) ? 'bg-orbita-blue text-white' : 'text-gray-400 hover:bg-orbita-light' }}">
                            All Products
                        </button>
                        @foreach($categories as $category)
                            <button wire:click="$set('selectedCategory', {{ $category->id }})" 
                                    class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition {{ $selectedCategory == $category->id ? 'bg-orbita-blue text-white' : 'text-gray-400 hover:bg-orbita-light' }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </aside>

            <main class="w-full lg:w-3/4">
                <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Showing {{ $products->total() }} items</p>
                    <select wire:model.live="sortPrice" class="border-none bg-transparent text-xs font-black text-orbita-blue uppercase tracking-widest focus:ring-0 cursor-pointer">
                        <option value="asc">Price: Low to High</option>
                        <option value="desc">Price: High to Low</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach($products as $product)
                        <div class="group bg-white rounded-[2.5rem] overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 relative">
                            
                            <a href="{{ route('product.show', $product->slug) }}" class="h-64 overflow-hidden bg-orbita-light relative block">
                                <img src="{{ asset('storage/' . ($product->images[0] ?? 'default.jpg')) }}" 
                                     class="w-full h-full object-contain p-8 group-hover:scale-110 transition-transform duration-700"
                                     alt="{{ $product->name }}">
                                
                                @if($product->stock_quantity <= 0)
                                    <span class="absolute top-4 left-4 bg-red-500 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase">Out of Stock</span>
                                @endif
                            </a>

                            <div class="p-8">
                                <p class="text-[10px] font-black text-orbita-gold uppercase tracking-[0.2em] mb-2">{{ $product->category->name }}</p>
                                
                                <a href="{{ route('product.show', $product->slug) }}" class="block group/title">
                                    <h2 class="text-xl font-black text-orbita-blue uppercase tracking-tighter mb-4 h-14 overflow-hidden group-hover/title:text-orbita-gold transition-colors">
                                        {{ $product->name }}
                                    </h2>
                                </a>
                                
                                <div class="flex items-center justify-between mt-6">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Price</span>
                                        <span class="text-xl font-black text-orbita-blue">KES {{ number_format($product->price) }}</span>
                                    </div>
                                    
                                    <a href="{{ route('product.show', $product->slug) }}" class="w-12 h-12 bg-orbita-blue text-white rounded-2xl flex items-center justify-center hover:bg-orbita-gold transition-colors shadow-lg shadow-orbita-blue/20 hover:shadow-orbita-gold/40">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            </main>

        </div>
    </div>
</div>