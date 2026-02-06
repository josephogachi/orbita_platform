@extends('layouts.public')

@section('content')
<section class="py-12 md:py-24 bg-white">
    <div class="container mx-auto px-4 md:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 mb-32">
            
            {{-- 1. PREMIUM GALLERY --}}
            <div x-data="{ activeImg: '{{ asset('storage/' . ($product->images[0] ?? '')) }}' }" class="space-y-6">
                <div class="relative aspect-square rounded-[4rem] bg-[#F8F9FA] border border-gray-100 p-12 flex items-center justify-center overflow-hidden group shadow-2xl shadow-gray-200/50">
                    <img :src="activeImg" class="max-w-full max-h-full object-contain mix-blend-multiply transform transition-transform duration-700 group-hover:scale-110">
                </div>
                
                <div class="flex gap-4 overflow-x-auto no-scrollbar pb-2">
                    @foreach($product->images as $img)
                        <button @click="activeImg = '{{ asset('storage/'.$img) }}'" 
                                class="w-24 h-24 flex-shrink-0 rounded-3xl border-2 p-3 transition-all duration-300"
                                :class="activeImg === '{{ asset('storage/'.$img) }}' ? 'border-orbita-gold bg-white shadow-lg scale-105' : 'border-transparent bg-gray-50 opacity-60 hover:opacity-100'">
                            <img src="{{ asset('storage/'.$img) }}" class="w-full h-full object-contain mix-blend-multiply">
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- 2. PRODUCT INFO --}}
            <div class="flex flex-col justify-center">
                <div class="mb-8">
                    <span class="inline-block px-4 py-1.5 bg-orbita-blue/5 text-orbita-blue rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-6">
                        SKU: {{ $product->sku }}
                    </span>
                    <h1 class="text-5xl md:text-7xl font-black text-orbita-blue uppercase tracking-tighter leading-none mb-6">
                        {{ $product->name }}
                    </h1>
                    <p class="text-4xl font-black text-orbita-gold tracking-tighter italic mb-8">
                        KES {{ number_format($product->price) }}
                    </p>
                </div>

                {{-- Clean Description (Handling Markdown Asterisks) --}}
                <div class="prose prose-lg text-gray-500 mb-12 max-w-none font-medium leading-relaxed">
                    {!! Str::markdown($product->description) !!}
                </div>

                {{-- 3. UNIFORM CLASSIC BUTTONS --}}
<div class="space-y-6">
    {{-- Primary Actions: Add to Cart & WhatsApp (Now inside Livewire) --}}
    <div class="w-full">
        <livewire:add-to-cart :product="$product" :key="'atc-'.$product->id" />
    </div>

    {{-- Secondary Action: Installation/Corporate Quote --}}
    <div class="pt-2">
        <a href="{{ route('quotes.create', ['product_id' => $product->id]) }}" 
   class="flex items-center justify-center px-8 py-6 border-2 border-orbita-blue text-orbita-blue font-black uppercase tracking-widest text-[11px] rounded-[2rem] hover:bg-orbita-blue hover:text-white transition-all duration-300">
    Request Project Quote
</a>
    </div>

    {{-- 🛡️ TRUST BADGE MINI-GRID --}}
    <div class="grid grid-cols-2 gap-4 pt-4">
        <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
            <div class="text-orbita-gold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <span class="text-[9px] font-black uppercase tracking-widest text-gray-500">Official Warranty</span>
        </div>
        <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
            <div class="text-orbita-gold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <span class="text-[9px] font-black uppercase tracking-widest text-gray-500">Lifetime Support</span>
        </div>
    </div>
</div>
            </div>
        </div>

       {{-- 4. TECHNICAL SPECIFICATIONS (Datasheet Grid) --}}
@if($product->technical_specs)
<div class="mt-20 border-t border-gray-100 pt-20">
    <div class="flex items-center gap-4 mb-12">
        <h2 class="text-3xl font-black text-orbita-blue uppercase tracking-tighter">Technical Datasheet</h2>
        <div class="h-[2px] flex-1 bg-gradient-to-r from-orbita-gold/50 to-transparent"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-8">
        @foreach(explode("\n", $product->technical_specs) as $spec)
            @php 
                // Split "Weight: 2.5kg" into ["Weight", "2.5kg"] if a colon exists
                $parts = explode(':', $spec, 2); 
            @endphp
            
            @if(trim($spec))
                <div class="group flex flex-col border-b border-gray-100 pb-4 hover:border-orbita-gold transition-colors duration-500">
                    @if(count($parts) > 1)
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-orbita-gold mb-1 opacity-70 group-hover:opacity-100 transition-opacity">
                            {{ trim($parts[0]) }}
                        </span>
                        <span class="text-sm font-bold text-orbita-blue uppercase tracking-tight">
                            {{ trim($parts[1]) }}
                        </span>
                    @else
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-orbita-gold"></span>
                            <span class="text-sm font-bold text-orbita-blue uppercase tracking-tight">
                                {{ trim($spec) }}
                            </span>
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</div>
@endif

{{-- 5. PROTECTED DOWNLOADS --}}
@if($product->pdf_datasheet)
<div class="mt-12 p-8 bg-gray-50 rounded-[3rem] border border-dashed border-gray-200 flex flex-col md:flex-row items-center justify-between gap-6">
    <div class="flex items-center gap-5">
        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm text-red-500">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9v-2h2v2zm0-4H9V7h2v5z"/></svg>
        </div>
        <div>
            <h4 class="font-black text-orbita-blue uppercase tracking-tight">Technical PDF Catalog</h4>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Size: 2.4 MB • Format: PDF</p>
        </div>
    </div>

    @auth
        <a href="{{ asset('storage/' . $product->pdf_datasheet) }}" download
           class="px-8 py-4 bg-orbita-blue text-white rounded-full font-black uppercase tracking-widest text-[10px] hover:bg-orbita-gold transition-all shadow-xl">
            Download Now
        </a>
    @else
        <a href="{{ route('register') }}" 
           class="px-8 py-4 border-2 border-orbita-blue text-orbita-blue rounded-full font-black uppercase tracking-widest text-[10px] flex items-center gap-2 hover:bg-orbita-blue hover:text-white transition-all">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            Login to Download
        </a>
    @endauth
</div>
@endif
        {{-- 5. RELATED SOLUTIONS --}}
        @if($relatedProducts->count() > 0)
        <div>
            <h3 class="text-3xl font-black text-orbita-blue uppercase tracking-tighter mb-12">Related Solutions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($relatedProducts as $related)
                    <a href="{{ route('product.show', $related->slug) }}" class="group">
                        <div class="bg-gray-50 rounded-[3rem] p-8 mb-6 border border-transparent group-hover:border-orbita-gold/20 group-hover:bg-white group-hover:shadow-2xl transition-all duration-500">
                            <img src="{{ asset('storage/' . ($related->images[0] ?? '')) }}" class="h-48 mx-auto object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-700">
                        </div>
                        <h4 class="font-black text-orbita-blue uppercase text-sm mb-2 group-hover:text-orbita-gold transition">{{ $related->name }}</h4>
                        <p class="font-bold text-orbita-gold">KES {{ number_format($related->price) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection