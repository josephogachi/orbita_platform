@extends('layouts.public')

@section('content')
<section class="py-12 md:py-20 bg-white">
    <div class="container mx-auto px-4 md:px-8">
        
        {{-- 1. Classy Breadcrumbs --}}
        <nav class="flex mb-12 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">
            <a href="/" class="hover:text-orbita-gold transition">Home</a>
            <span class="mx-3 text-gray-200">/</span>
            <a href="#" class="hover:text-orbita-gold transition">{{ $product->category->name ?? 'Category' }}</a>
            <span class="mx-3 text-orbita-gold">/</span>
            <span class="text-orbita-blue">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-24">
            
            {{-- 2. STICKY PRODUCT GALLERY --}}
            <div x-data="{ activeImg: '{{ asset('storage/' . ($product->images[0] ?? '')) }}' }" class="lg:sticky lg:top-32 h-max">
                <div class="relative rounded-[3rem] bg-orbita-light p-8 md:p-12 overflow-hidden mb-8 border border-gray-100 shadow-inner group">
                    {{-- Active Image Display --}}
                    <div class="flex items-center justify-center min-h-[400px] md:min-h-[500px]">
                        <img :src="activeImg" 
                             class="max-w-full max-h-[500px] object-contain mix-blend-multiply transition-all duration-700 transform group-hover:scale-110"
                             alt="{{ $product->name }}">
                    </div>

                    {{-- Discount Badge if applicable --}}
                    @if($product->discount_percent > 0)
                        <div class="absolute top-8 left-8 bg-red-600 text-white font-black text-xs px-5 py-2 rounded-full shadow-xl">
                            SAVE {{ $product->discount_percent }}%
                        </div>
                    @endif
                </div>
                
                {{-- Thumbnails --}}
                <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                    @foreach($product->images as $img)
                        <button @click="activeImg = '{{ asset('storage/'.$img) }}'" 
                                class="w-20 h-20 md:w-24 md:h-24 rounded-2xl border-2 p-2 transition-all duration-300 transform hover:scale-105"
                                :class="activeImg === '{{ asset('storage/'.$img) }}' ? 'border-orbita-gold bg-white shadow-lg' : 'border-transparent bg-gray-50 opacity-60 hover:opacity-100'">
                            <img src="{{ asset('storage/'.$img) }}" class="w-full h-full object-contain mix-blend-multiply">
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- 3. PRODUCT INFORMATION --}}
            <div class="flex flex-col">
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="bg-orbita-blue text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">
                            SKU: {{ $product->sku }}
                        </span>
                        @if($product->stock_quantity > 0)
                            <span class="bg-green-100 text-green-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> In Stock
                            </span>
                        @endif
                    </div>

                    <h1 class="text-4xl md:text-6xl font-black text-orbita-blue uppercase tracking-tighter leading-[1.1] mb-6">
                        {{ $product->name }}
                    </h1>
                    
                    <div class="flex items-baseline gap-4 mb-8">
                        <span class="text-4xl font-black text-orbita-gold tracking-tighter">
                            KES {{ number_format($product->price) }}
                        </span>
                        @if($product->old_price && $product->old_price > $product->price)
                            <span class="text-xl text-gray-300 line-through font-bold">
                                KES {{ number_format($product->old_price) }}
                            </span>
                        @endif
                    </div>

                    <div class="prose prose-sm text-gray-500 max-w-none mb-10 border-l-4 border-orbita-gold/20 pl-6 py-2">
                        <p class="text-lg leading-relaxed font-medium italic">"{{ $product->description }}"</p>
                    </div>
                </div>

                {{-- 4. TECHNICAL SPECS GRID --}}
                @if($product->technical_specs)
                <div class="bg-orbita-light rounded-[2.5rem] p-8 md:p-10 mb-12 border border-gray-100">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-8 h-8 bg-orbita-blue text-white rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                        </div>
                        <h3 class="font-black text-orbita-blue uppercase text-sm tracking-[0.2em]">Technical Datasheet</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        @foreach(explode("\n", $product->technical_specs) as $spec)
                            @if(trim($spec))
                            <div class="flex items-center gap-3 py-3 border-b border-gray-200/50 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-orbita-gold group-hover:scale-150 transition-transform"></span>
                                <span class="text-sm font-bold text-gray-600 uppercase tracking-tight">{{ $spec }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- 5. ACTION AREA --}}
                <div class="flex flex-col sm:flex-row gap-6 mb-12">
                    <div class="flex-grow">
                        <livewire:add-to-cart :product="$product" />
                    </div>
                    
                    {{-- Secondary Action: Book Quotation --}}
                    <a href="{{ route('contact') }}" class="flex items-center justify-center px-8 py-5 border-2 border-orbita-blue text-orbita-blue font-black uppercase tracking-widest text-xs rounded-full hover:bg-orbita-blue hover:text-white transition-all duration-300">
                        Get Custom Quote
                    </a>
                </div>

                {{-- 6. TRUST FOOTER --}}
                <div class="grid grid-cols-3 gap-4 border-t border-gray-100 pt-10">
                    <div class="text-center">
                        <div class="text-orbita-gold mb-2 flex justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">2 Year Warranty</span>
                    </div>
                    <div class="text-center">
                        <div class="text-orbita-gold mb-2 flex justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">Kenya Tech Support</span>
                    </div>
                    <div class="text-center">
                        <div class="text-orbita-gold mb-2 flex justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">Hotel Ready</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection