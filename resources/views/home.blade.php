@extends('layouts.public')

@if(session('error'))
    <div onclick="this.remove()" class="fixed top-10 right-4 z-50 bg-red-600 text-white px-6 py-4 rounded-xl shadow-2xl cursor-pointer animate-bounce">
        <span class="font-bold">Error:</span> {{ session('error') }}
    </div>
@endif

@if(session('info'))
    <div onclick="this.remove()" class="fixed top-10 right-4 z-50 bg-blue-600 text-white px-6 py-4 rounded-xl shadow-2xl cursor-pointer">
        <span class="font-bold">Notice:</span> {{ session('info') }}
    </div>
@endif

@section('content')

{{-- ==========================================
     HERO E-COMMERCE GRID (Final Layout)
     ========================================== --}}
<section class="pt-4 pb-8 lg:pb-12 px-4 container mx-auto">
    
    {{-- Main Grid: Using ONLY pre-compiled heights to prevent collapsing on live server --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 h-[350px] md:h-[400px] lg:h-[480px]">
        
        {{-- 1. LEFT COLUMN: CATEGORIES (Brand Adjusted - Hidden on Mobile) --}}
        <div class="hidden lg:flex flex-col lg:col-span-1 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden h-full">
            {{-- Header --}}
            <div class="bg-orbita-blue text-white px-5 py-4 font-black uppercase tracking-widest text-xs flex items-center gap-2 shadow-md z-10" 
                 style="background-color: #021256 !important;">
                <svg class="w-5 h-5 text-orbita-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                All Departments
            </div>
            
            {{-- Category List --}}
            <ul class="flex-1 overflow-y-auto py-2 bg-orbita-blue" style="background-color: #021256 !important;">
                @if(isset($categories) && $categories->count() > 0)
                    @foreach($categories->take(7) as $category)
                    <li>
                        <a href="{{ route('products.index', ['category' => $category->slug ?? $category->id]) }}" 
                           class="flex items-center justify-between px-5 py-3 text-sm font-semibold text-gray-300 hover:text-white hover:bg-white/10 border-b border-white/5 transition-all duration-300 group">
                            <span class="group-hover:translate-x-1 transition-transform truncate pr-2 group-hover:text-orbita-gold">{{ $category->name }}</span>
                            <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity text-orbita-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </li>
                    @endforeach
                    
                    {{-- Other Categories Link --}}
                    @if($categories->count() > 7)
                    <li>
                        <a href="{{ route('products.index') }}" 
                           class="flex items-center justify-between px-5 py-3 text-sm font-black text-orbita-gold hover:text-white hover:bg-white/10 transition-colors">
                            <span>Other Categories</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </li>
                    @endif
                @else
                    <li class="px-5 py-3 text-sm text-gray-400">No categories found.</li>
                @endif
            </ul>
        </div>

        {{-- 2. CENTER COLUMN: MAIN SLIDER (Visible on all devices) --}}
        <div class="lg:col-span-2 relative rounded-xl overflow-hidden shadow-lg border border-gray-100 bg-white h-full w-full">
            @if(isset($promotions) && $promotions->count() > 0)
                <div x-data="{ active: 0, total: {{ $promotions->count() }} }" 
                     x-init="setInterval(() => { active = (active + 1) % total }, 6000)"
                     class="w-full h-full relative">
                    
                    @foreach($promotions as $index => $promo)
                        <div x-show="active === {{ $index }}"
                             x-cloak
                             class="absolute inset-0 w-full h-full flex items-center justify-center bg-white"
                             style="{{ $index === 0 ? 'display:flex;' : 'display:none;' }}">
                            
                            @php $cleanFile = str_replace('public/', '', $promo->file_path ?? $promo->image); @endphp

                            @if($promo->type === 'video')
                                <video class="w-full h-full object-contain" autoplay muted loop playsinline>
                                    <source src="{{ asset('storage/' . $cleanFile) }}" type="video/mp4">
                                </video>
                            @else
                                {{-- Hardcoded to object-contain so 1920x1080 images fit perfectly without cropping --}}
                                <img src="{{ asset('storage/' . $cleanFile) }}" 
                                     class="w-full h-full object-contain" 
                                     alt="{{ $promo->title }}"
                                     onerror="this.src='https://placehold.co/1920x1080/0f172a/ffffff?text=Image+Missing'">
                            @endif

                            {{-- Text Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent flex flex-col justify-center px-8 md:px-12 pointer-events-none">
                                <div class="max-w-md pointer-events-auto">
                                    <h1 class="text-3xl md:text-5xl font-black leading-tight uppercase text-white drop-shadow-lg">
                                        {{ $promo->title }}
                                    </h1>
                                    <a href="{{ $promo->link_url ?? '#' }}" class="inline-block bg-orbita-gold text-white px-6 py-2.5 md:px-8 md:py-3 rounded-full font-black text-[10px] md:text-xs uppercase tracking-widest hover:bg-white hover:text-black transition-colors shadow-xl mt-4 md:mt-6">
                                        {{ $promo->button_text ?? 'Explore' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="absolute inset-0 flex items-center justify-center text-gray-500 bg-gray-100">No Promotions</div>
            @endif
        </div>

        {{-- 3. RIGHT COLUMN: 3 PROMO ADS (Hidden on Mobile/Tablet) --}}
        <div class="hidden lg:flex flex-col lg:col-span-1 gap-4 h-full">
            @if(isset($sideAds) && $sideAds->count() > 0)
                @foreach($sideAds->take(3) as $ad)
                    @php 
                        $cleanPath = str_replace(['public/', 'storage/'], '', $ad->image_path ?? $ad->image ?? ''); 
                    @endphp
                    <a href="{{ $ad->link_url ?? '#' }}" class="flex-1 relative rounded-xl overflow-hidden shadow-md bg-gray-200 block h-full w-full group">
                        <img src="{{ asset('storage/' . ltrim($cleanPath, '/')) }}" 
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" 
                             alt="{{ $ad->title ?? 'Ad' }}"
                             onerror="this.src='https://placehold.co/400x400/e5e7eb/9ca3af?text=Image+Error'">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent flex flex-col justify-end p-4 pointer-events-none">
                            @if(!empty($ad->badge_text))
                                <span class="inline-block text-[9px] font-bold bg-orbita-gold text-gray-900 px-2 py-0.5 rounded-sm uppercase tracking-widest mb-1 shadow w-max">{{ $ad->badge_text }}</span>
                            @endif
                            @if(!empty($ad->title))
                                <h3 class="text-white font-black text-sm uppercase leading-tight drop-shadow-md">{{ $ad->title }}</h3>
                            @endif
                        </div>
                    </a>
                @endforeach
                
                {{-- Fill empty slots --}}
                @for($i = $sideAds->count(); $i < 3; $i++)
                    <div class="flex-1 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center h-full w-full">
                        <span class="text-gray-300 font-bold uppercase text-xs">Ad Slot</span>
                    </div>
                @endfor
            @else
                {{-- If no database entries --}}
                @for($i = 0; $i < 3; $i++)
                    <div class="flex-1 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center h-full w-full">
                        <span class="text-gray-300 font-bold uppercase text-xs">Ad Slot {{ $i + 1 }}</span>
                    </div>
                @endfor
            @endif
        </div>
        
    </div>
</section>

    {{-- 2. CLIENTS MARQUEE (SEO ENHANCED) --}}
    <section class="py-28 relative bg-white overflow-hidden border-b border-gray-100">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-gray-100 rounded-full blur-3xl opacity-60"></div>
            <div class="absolute top-1/2 right-0 w-[500px] h-[500px] bg-orbita-gold/5 rounded-full blur-[100px] translate-x-1/2"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10 text-center mb-24">
            <span class="text-orbita-gold font-bold uppercase tracking-[0.3em] text-sm mb-4 block animate-pulse-slow">
                Top Hospitality Partners
            </span>
            <h2 class="text-5xl md:text-7xl font-black text-orbita-blue mb-8 tracking-tighter leading-tight">
                Trusted by Leading <br class="hidden md:block"> Hotels in Kenya
            </h2>
            <p class="text-gray-500 max-w-3xl mx-auto text-xl leading-relaxed font-medium">
                We are proud to supply and install premium wholesale hotel smart locks, minibars, and hospitality room accessories for the most prestigious resorts across Nairobi, Mombasa, and East Africa.
            </p>
        </div>

        <div class="relative w-full overflow-hidden group">
            <div class="absolute top-0 left-0 z-10 h-full w-32 md:w-80 bg-gradient-to-r from-white via-white/90 to-transparent pointer-events-none"></div>
            <div class="absolute top-0 right-0 z-10 h-full w-32 md:w-80 bg-gradient-to-l from-white via-white/90 to-transparent pointer-events-none"></div>

            <div class="flex animate-marquee gap-16 md:gap-32 items-center whitespace-nowrap py-4 hover:[animation-play-state:paused]">
                @for($i=0; $i<4; $i++) 
                    @foreach($clients as $client)
                        <div class="flex-shrink-0 flex flex-col items-center justify-center transition-all duration-500 transform hover:scale-105 cursor-pointer px-4">
                            <img src="{{ asset('storage/'.$client->logo_path) }}" 
                                 class="h-20 md:h-28 w-auto max-w-[200px] md:max-w-[350px] object-contain grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition duration-500"
                                 alt="Orbita Kenya Hotel Client - {{ $client->name ?? 'Partner' }}">
                        </div>
                    @endforeach
                @endfor
            </div>
        </div>
    </section>

    {{-- 3. PRODUCT SECTIONS --}}
@php
    $productSections = [
        [
            'title' => 'New Arrivals', 
            'subtitle' => 'JUST IN',
            'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6',
            'products' => $newArrivals,
            'color' => 'orbita-blue'
        ],
        [
            'title' => 'Hot Selling', 
            'subtitle' => 'FLASH SALE',
            'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
            'products' => $hotSelling,
            'color' => 'red-600'
        ],
        [
            'title' => 'Sponsored Products', 
            'subtitle' => 'FEATURED',
            'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z',
            'products' => $sponsoredProducts,
            'color' => 'orbita-gold'
        ]
    ];
@endphp

@foreach($productSections as $section)
    @if($section['products']->count() > 0)
    <section class="py-12 container mx-auto px-4" x-data="{ 
        prev() { this.$refs.slider.scrollBy({ left: -400, behavior: 'smooth' }) },
        next() { this.$refs.slider.scrollBy({ left: 400, behavior: 'smooth' }) }
    }">
        {{-- Section Header --}}
        <div class="bg-orbita-blue rounded-t-[2.0rem] p-5 md:px-10 flex flex-col md:flex-row justify-between items-center gap-4 border-b border-white/10 shadow-lg">
            <div class="flex items-center gap-4">
                <div class="bg-orbita-gold p-2 rounded-xl shadow-glow">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $section['icon'] }}" />
                    </svg>
                </div>
                <div>
                    <span class="text-orbita-gold text-[10px] font-black tracking-[0.3em] uppercase block leading-none mb-1">{{ $section['subtitle'] }}</span>
                    <h2 class="text-white font-black uppercase tracking-tighter text-2xl leading-none">{{ $section['title'] }}</h2>
                </div>
            </div>

            <div class="flex items-center gap-6">
                @if($settings->show_countdown && $section['title'] === 'Hot Selling')
                <div class="flex items-center gap-3 text-white">
                    <span class="text-[10px] text-orbita-gold font-black uppercase tracking-widest hidden lg:block text-right leading-tight">Ends<br>In:</span>
                    <div x-data="countdown('{{ $settings->countdown_end }}')" class="flex gap-2 font-mono text-xl">
                        <div class="bg-white/10 px-2 py-1 rounded flex flex-col items-center"><span x-text="hours">00</span><small class="text-[8px] opacity-50">HR</small></div>
                        <div class="bg-white/10 px-2 py-1 rounded flex flex-col items-center"><span x-text="minutes">00</span><small class="text-[8px] opacity-50">MIN</small></div>
                        <div class="bg-white/10 px-2 py-1 rounded flex flex-col items-center"><span x-text="seconds">00</span><small class="text-[8px] opacity-50">SEC</small></div>
                    </div>
                </div>
                @endif
                
                {{-- Navigation Arrows --}}
                <div class="hidden md:flex items-center gap-2">
                    <button @click="prev()" class="bg-white/10 hover:bg-orbita-gold text-white p-2 rounded-full transition border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="next()" class="bg-white/10 hover:bg-orbita-gold text-white p-2 rounded-full transition border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                <a href="{{ route('products.index') }}" class="bg-white/10 hover:bg-orbita-gold text-white px-5 py-2.5 rounded-full font-bold text-[10px] uppercase tracking-widest transition-all border border-white/20 flex items-center gap-2">
                    View Catalog
                </a>
            </div>
        </div>

        {{-- Slider Container --}}
        <div class="bg-white rounded-b-[2.5rem] p-8 shadow-2xl border-x border-b border-gray-100 relative">
            <div x-ref="slider" class="flex overflow-x-auto gap-8 no-scrollbar snap-x snap-mandatory scroll-smooth pb-4">
                @foreach($section['products'] as $product)
                <div class="snap-start flex-shrink-0 w-[280px] md:w-[240px] group flex flex-col relative bg-white h-full">
                    
                    @if($product->discount_percent > 0)
                    <div class="absolute top-2 right-2 bg-red-600 text-white font-black text-[10px] px-3 py-1 rounded-full z-10 shadow-lg">
                        SAVE {{ $product->discount_percent }}%
                    </div>
                    @endif

                    <div class="h-56 flex items-center justify-center mb-6 relative overflow-hidden rounded-[2rem] bg-orbita-light border border-transparent group-hover:border-orbita-gold/20 transition-all duration-500 shadow-inner">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                 alt="{{ $product->name }} - Hospitality Hardware Kenya"
                                 class="max-h-40 w-auto object-contain mix-blend-multiply group-hover:scale-110 transition duration-700 p-4">
                        @else
                            <div class="text-gray-300 text-[10px] font-bold uppercase tracking-widest">No Image</div>
                        @endif
                        
                        <div class="absolute inset-0 bg-orbita-blue/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center backdrop-blur-[2px]">
                            <a href="{{ route('product.show', $product->slug) }}" class="bg-white text-orbita-blue px-6 py-2 rounded-full font-black text-[10px] uppercase tracking-widest shadow-xl transform translate-y-4 group-hover:translate-y-0 transition duration-300 hover:bg-orbita-gold hover:text-white">
                                View Details
                            </a>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col px-1">
                        <span class="text-[9px] font-bold text-orbita-gold uppercase tracking-[0.2em] mb-1">
                            {{ $product->category->name ?? 'Orbita' }}
                        </span>
                        <h3 class="text-sm font-black text-orbita-blue uppercase leading-tight mb-3 group-hover:text-orbita-gold transition line-clamp-2 h-10">
                            {{ $product->name }}
                        </h3>
                        
                        <div class="flex flex-col mb-4">
                            <div class="flex items-baseline gap-2">
                                <span class="text-xl font-black text-orbita-blue tracking-tighter">
                                    KES {{ number_format($product->price) }}
                                </span>
                            </div>
                            @if($product->old_price && $product->old_price > $product->price)
                            <span class="text-[10px] text-gray-400 line-through font-bold">
                                KES {{ number_format($product->old_price) }}
                            </span>
                            @endif
                        </div>

                        <div class="mt-auto">
                            <div class="flex justify-between text-[9px] font-black uppercase mb-1.5">
                                <span class="{{ $product->stock_quantity < 10 ? 'text-red-500 animate-pulse' : 'text-gray-400' }}">
                                    {{ $product->stock_quantity }} items left
                                </span>
                                <span class="text-gray-300">Stock</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                @php 
                                    $pWidth = min(($product->stock_quantity / 100) * 100, 100); 
                                @endphp
                                <div class="h-full rounded-full transition-all duration-1000 bg-gradient-to-r {{ $product->stock_quantity < 10 ? 'from-red-500 to-red-400' : 'from-orbita-gold to-yellow-400' }}" 
                                     style="width: {{ $pWidth }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endforeach

   {{-- 4. COLLABORATE CTA - FORCED DARK BACKGROUND (LOCALIZED) --}}
    <section class="py-24 container mx-auto px-4">
        <div class="relative rounded-[3rem] md:rounded-[4.5rem] overflow-hidden shadow-2xl border border-white/10" 
             style="background-color: #021256 !important;">
            
            {{-- Creative Abstract Layers --}}
            <div class="absolute inset-0 z-0 pointer-events-none">
                <div class="absolute top-[-10%] right-[-5%] w-[60%] h-[80%] bg-orbita-blue/20 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-[-10%] left-[-5%] w-[40%] h-[60%] bg-orbita-gold/10 rounded-full blur-[100px]"></div>
                <div class="absolute inset-0 opacity-[0.1]" 
                     style="background-image: radial-gradient(circle, #d8aa3f 1px, transparent 1px); background-size: 40px 40px;">
                </div>
            </div>

            {{-- Content Container --}}
            <div class="relative z-10 px-6 py-20 md:p-32 text-center">
                <div class="max-w-4xl mx-auto">
                    
                    <span class="inline-block px-5 py-1.5 mb-10 border border-orbita-gold/30 bg-orbita-gold/5 text-orbita-gold text-[9px] md:text-xs font-black uppercase tracking-[0.5em] rounded-full">
                        Premium Hotel Security Suppliers in Kenya
                    </span>

                    {{-- High-Contrast Heading --}}
                    <h2 class="text-white text-3xl md:text-7xl font-black mb-10 leading-[1.1] uppercase tracking-tighter">
                        Innovating <br class="hidden md:block"> 
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orbita-gold via-yellow-400 to-orbita-gold">Hospitality Tech</span> 
                        Solutions.
                    </h2>

                    <p class="text-gray-400 text-sm md:text-xl font-medium max-w-2xl mx-auto mb-14 leading-relaxed">
                        Join the elite hotels and resorts across Nairobi, Mombasa, and East Africa leveraging Orbita's wholesale RFID smart locks, silent minibars, and world-class installation services.
                    </p>

                    {{-- Button Group: Classy & Compact --}}
                    <div class="flex flex-wrap justify-center items-center gap-6">
                        <a href="{{ route('contact') }}" class="w-max">
                            <button class="px-10 md:px-14 py-4 md:py-6 bg-orbita-gold text-white font-black uppercase tracking-widest text-[10px] md:text-xs rounded-full hover:scale-105 transition-all duration-300 shadow-2xl shadow-orbita-gold/30">
                                Get a Quote
                            </button>
                        </a>

                       <a href="{{ route('catalog.download') }}" 
                           class="inline-flex items-center gap-2 bg-orbita-gold text-white px-6 py-3 md:px-8 md:py-4 rounded-xl font-black uppercase tracking-widest hover:bg-orbita-blue transition-colors duration-300 shadow-xl text-xs md:text-sm">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>View Full Catalog</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Background Branded Watermark --}}
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 pointer-events-none opacity-[0.05] select-none">
                <h3 class="text-[12vw] font-black text-white whitespace-nowrap tracking-tighter">ORBITA KENYA</h3>
            </div>
        </div>
    </section>

{{-- 5. PARTNER CTA - SLIM MINIMAL BANNER (SEO ENHANCED) --}}
    <section class="pb-16 container mx-auto px-4">
        <div class="bg-white rounded-[1.5rem] shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col md:flex-row items-center justify-between p-6 md:px-10 md:py-8 gap-6 relative overflow-hidden">
            
            {{-- Minimal Gold Accent Line --}}
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-orbita-gold"></div>

            {{-- Icon & Text Content --}}
            <div class="flex items-center gap-5 md:gap-6 flex-1 w-full pl-2">
                
                {{-- Subtle Icon --}}
                <div class="hidden sm:flex shrink-0 w-12 h-12 bg-orbita-blue/5 text-orbita-blue rounded-full items-center justify-center border border-orbita-blue/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[8px] md:text-[9px] font-black uppercase tracking-widest text-orbita-gold bg-orbita-gold/10 px-2 py-0.5 rounded-sm">
                            B2B Reseller Program
                        </span>
                    </div>
                    <h2 class="text-lg md:text-xl font-black text-orbita-blue uppercase tracking-tight leading-tight">
                        Become an Official Orbita Kenya Partner
                    </h2>
                    <p class="text-gray-500 text-[11px] md:text-xs mt-1 max-w-2xl">
                        Access exclusive wholesale pricing on <strong>hotel smart locks, room safes, and minibars</strong>. Join our trusted network of certified hospitality suppliers across East Africa.
                    </p>
                </div>
            </div>

            {{-- CTA Button --}}
            <div class="shrink-0 w-full md:w-auto mt-2 md:mt-0">
                <a href="{{ route('partnership') }}" class="block text-center md:inline-block px-8 py-3.5 bg-orbita-blue text-white font-black uppercase text-[10px] tracking-widest rounded-xl hover:bg-orbita-gold transition-colors shadow-lg shadow-orbita-blue/10 w-full md:w-auto">
                    Apply Now
                </a>
            </div>

        </div>
    </section>

    {{-- 6. TESTIMONIALS (SEO ENHANCED) --}}
    <section class="py-28 relative bg-white overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-gray-50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-20">
                <span class="text-orbita-gold font-bold uppercase tracking-[0.3em] text-xs mb-4 block">Local Success Stories</span>
                <h2 class="text-4xl md:text-6xl font-black text-orbita-blue tracking-tighter">What Kenyan Hoteliers Say</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($testimonials as $testimonial)
                    <div class="bg-orbita-light p-10 rounded-[2.5rem] border border-gray-100 relative group hover:bg-white hover:shadow-2xl hover:border-white transition-all duration-500">
                        <div class="absolute top-8 right-10 text-orbita-gold opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V12C14.017 12.5523 13.5693 13 13.017 13H11.017C10.4647 13 10.017 12.5523 10.017 12V9C10.017 7.34315 11.3601 6 13.017 6H19.017C20.6739 6 22.017 7.34315 22.017 9V15C22.017 17.7614 19.7784 20 17.017 20H14.017V21H14.017ZM2.017 21L2.017 18C2.017 16.8954 2.91243 16 4.017 16H7.017C7.56928 16 8.017 15.5523 8.017 15V9C8.017 8.44772 7.56928 8 7.017 8H3.017C2.46472 8 2.017 8.44772 2.017 9V12C2.017 12.5523 1.56928 13 1.017 13H-0.983C-1.53528 13 -2.017 12.5523 -2.017 12V9C-2.017 7.34315 -0.67385 6 0.983 6H7.017C8.67385 6 10.017 7.34315 10.017 9V15C10.017 17.7614 7.77843 20 5.017 20H2.017V21H2.017Z" /></svg>
                        </div>

                        <div class="flex gap-1 mb-6">
                            @for($i = 0; $i < ($testimonial->rating ?? 5); $i++)
                                <svg class="w-4 h-4 text-orbita-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            @endfor
                        </div>

                        <p class="text-gray-600 italic leading-relaxed mb-8 relative z-10">
                            "{{ $testimonial->content }}"
                        </p>

                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-full overflow-hidden bg-gray-200 border-2 border-white shadow-sm">
                                @if($testimonial->image_path)
                                    <img src="{{ asset('storage/' . $testimonial->image_path) }}" class="w-full h-full object-cover" alt="Hotel Client {{ $testimonial->client_name }} - Orbita Kenya">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-orbita-blue text-white font-bold">
                                        {{ substr($testimonial->client_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-black text-orbita-blue uppercase text-xs tracking-widest">{{ $testimonial->client_name }}</h4>
                                <p class="text-[10px] text-orbita-gold font-bold uppercase">{{ $testimonial->role }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="lg:col-span-3 text-center py-10 text-gray-400">
                        <p class="text-xs uppercase tracking-widest font-bold">No testimonials added yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

   {{-- 7. WELCOME MODAL (Professional Elite Edition) --}}
<div x-data="{ 
        show: !localStorage.getItem('orbita_subscribed'),
        init() {
            setTimeout(() => { 
                if(!localStorage.getItem('orbita_subscribed')) this.show = true 
            }, 2000);
        },
        closeModal() {
            this.show = false;
            localStorage.setItem('orbita_subscribed', 'true');
        }
     }" 
     x-show="show" 
     x-cloak 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     class="fixed inset-0 z-[100] flex items-center justify-center px-4 backdrop-blur-md bg-orbita-blue/40"
     style="display: none;">

    <div class="bg-white rounded-[2.5rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] max-w-md w-full p-2 relative overflow-hidden border border-white/20">
        
        <div class="absolute top-0 left-0 w-full h-1.5 bg-orbita-gold"></div>

        <button @click="closeModal()" class="absolute top-5 right-5 z-20 w-8 h-8 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center hover:bg-orbita-blue hover:text-white transition-all duration-300">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="p-8 md:p-12 text-center bg-gray-50/50 rounded-[2rem] border border-gray-100">
            <div class="w-20 h-20 bg-orbita-blue text-orbita-gold rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-2xl rotate-3">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>

            <h2 class="text-3xl font-black text-orbita-blue uppercase tracking-tighter mb-3">
                Executive <span class="text-orbita-gold">Access</span>
            </h2>
            
            <p class="text-gray-500 text-base mb-8 leading-relaxed">
                Join our exclusive network of Kenyan hospitality managers for priority updates on hotel locks, minibars, and seasonal wholesale offers.
            </p>

            <form action="{{ route('subscribe.store') }}" method="POST" @submit="closeModal()" class="space-y-4">
                @csrf
                <div class="relative">
                    <input type="email" name="email" required placeholder="Work Email Address" 
                           class="w-full px-6 py-4 rounded-2xl border border-gray-200 bg-white shadow-sm focus:ring-2 focus:ring-orbita-gold focus:border-transparent outline-none text-gray-700 text-center transition-all">
                </div>
                
                <button type="submit" 
                        class="w-full py-4 bg-orbita-blue text-white font-black rounded-2xl hover:bg-orbita-gold hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 shadow-[0_10px_20px_rgba(2,18,86,0.2)] uppercase text-xs tracking-[0.2em]">
                    Unlock Access
                </button>
            </form>

            <p class="text-[10px] text-gray-400 mt-6 uppercase tracking-widest font-bold">
                Orbita Kenya • Secure & Confidential
            </p>
        </div>
    </div>
</div>

    {{-- 8. COOKIE CONSENT BANNER (Clean Professional) --}}
<div x-data="{ 
        show: !localStorage.getItem('orbita_cookies_accepted'),
        accept() {
            localStorage.setItem('orbita_cookies_accepted', 'true');
            this.show = false;
        }
     }" 
     x-show="show" 
     x-cloak
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="translate-y-20 opacity-0"
     x-transition:enter-end="translate-y-0 opacity-100"
     class="fixed bottom-6 left-6 right-6 md:right-auto md:max-w-md z-[90]"
     style="display: none;">

    <div class="bg-orbita-blue rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] border border-white/10 overflow-hidden">
        
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-orbita-gold"></div>

        <div class="p-6 md:p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="p-2.5 bg-white/10 rounded-xl text-orbita-gold shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg uppercase tracking-wider">Cookie Policy</h3>
                    <p class="text-gray-400 text-xs leading-relaxed mt-1">
                        We use cookies to enhance your experience and analyze our traffic. By clicking "Accept", you consent to our use of cookies.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button @click="accept()" 
                        class="flex-1 py-3 bg-orbita-gold text-orbita-blue font-black rounded-xl hover:bg-white transition-all duration-300 uppercase text-[10px] tracking-widest shadow-lg">
                    Accept All
                </button>
                
                <button @click="show = false" 
                        class="px-5 py-3 text-gray-400 hover:text-white font-bold uppercase text-[10px] tracking-widest transition-colors">
                    Decline
                </button>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('policy.privacy') }}" class="text-[9px] text-gray-500 uppercase tracking-widest hover:text-orbita-gold transition-colors underline decoration-gray-700">
                    Read our Privacy Policy
                </a>
            </div>
        </div>
    </div>
</div>
{{-- ==========================================
         8. SEO CONTENT BLOCK (Crucial for Google Ranking)
         ========================================== --}}
    <section class="py-16 bg-white border-t border-gray-100">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="prose prose-sm md:prose-base prose-gray max-w-none text-gray-500 text-justify md:text-left leading-relaxed">
                
                <h2 class="text-2xl font-black text-orbita-blue uppercase tracking-tight mb-4">
                    Premier Supplier of Hotel Smart Locks & Hospitality Technology in Kenya
                </h2>
                
                <p class="mb-4">
                    <strong>Orbita Kenya</strong> is the leading wholesale distributor of advanced hospitality security solutions and premium guest room amenities across East Africa. Based in Nairobi, we specialize in equipping hotels, resorts, and commercial properties with state-of-the-art <strong>RFID hotel door locks</strong>, robust EU mortise smart locks, and electronic digital room safes. Our hardware is engineered specifically for the rigorous security demands of the modern hospitality industry, ensuring the safety of your guests and the protection of your assets.
                </p>

                <h3 class="text-lg font-bold text-gray-800 uppercase tracking-tight mb-3 mt-8">
                    Wholesale Hotel Minibars & Room Accessories
                </h3>
                
                <p class="mb-4">
                    Beyond security, we help hoteliers elevate the guest experience. Our comprehensive wholesale catalog features <strong>silent hotel minibars</strong>, energy-efficient absorption fridges, elegant welcome trays, electric kettles, and wall-mounted hair dryers. Whether you are outfitting a luxury boutique lodge in Mombasa or upgrading a 500-room corporate hotel in Nairobi, Orbita Kenya maintains the inventory, supply chain, and expertise to support projects of any scale.
                </p>

                <h3 class="text-lg font-bold text-gray-800 uppercase tracking-tight mb-3 mt-8">
                    Why Choose Orbita Kenya for Your Project?
                </h3>
                
                <p>
                    Procuring from Orbita Kenya means partnering with the authorized regional distributor. We offer more than just premium hardware; our dedicated team provides comprehensive post-sale support, including <strong>professional lock installation</strong>, hotel management software training, and robust local warranties. By choosing local expertise, procurement managers eliminate international shipping headaches and ensure immediate technical support. Upgrade your property with Orbita today and join the elite network of secure, modern, and guest-ready hotels across Kenya.
                </p>

            </div>
        </div>
    </section>

@endsection