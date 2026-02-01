@extends('layouts.public')

@section('content')

    {{-- 1. HERO SECTION --}}
    {{-- 1. HERO SECTION --}}
    <section class="pt-4 lg:pt-6 pb-8 lg:pb-12 px-4 container mx-auto">
        {{-- 
           We remove the fixed height on mobile and use 'h-auto'.
           On desktop, we keep the lg:h-[680px].
        --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-auto lg:h-[680px]">
            
            {{-- 
               MOBILE FIX: 
               1. 'aspect-[16/9]' forces it to be a landscape rectangle on phones.
               2. 'h-auto' allows it to define its own height based on that aspect ratio.
            --}}
            <div x-data="{ active: 0, total: {{ $promotions->count() }} }" 
                 x-init="setInterval(() => { active = (active + 1) % total }, 6000)"
                 class="lg:col-span-3 relative rounded-[1.5rem] lg:rounded-[2.5rem] overflow-hidden shadow-2xl group border-2 lg:border-4 border-white bg-gray-900 aspect-[16/9] md:aspect-video lg:aspect-auto lg:h-full w-full">
                
                @forelse($promotions as $index => $promo)
                    <div x-show="active === {{ $index }}"
                         x-transition:enter="transition transform duration-1000"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute inset-0 w-full h-full">
                        
                        {{-- Content --}}
                        @if($promo->type === 'video')
                            <video class="w-full h-full object-cover" autoplay muted loop playsinline>
                                <source src="{{ asset('storage/'.$promo->file_path) }}" type="video/mp4">
                            </video>
                        @else
                            @php $img = $promo->file_path ?? $promo->image; @endphp
                            <img src="{{ asset('storage/'.$img) }}" class="w-full h-full object-cover object-center">
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-r from-orbita-blue/90 via-orbita-blue/30 to-transparent"></div>
                        
                        {{-- Text Container --}}
                        <div class="absolute inset-0 flex items-center px-6 md:px-20">
                            <div class="max-w-xl text-white space-y-2 md:space-y-6">
                                <span class="bg-orbita-gold text-white px-2 py-0.5 md:px-4 md:py-1.5 rounded-full text-[7px] md:text-[10px] font-black uppercase tracking-[0.2em] shadow-glow inline-block">
                                    Official Partner
                                </span>
                                
                                <h1 class="text-xl md:text-7xl font-black leading-tight uppercase drop-shadow-lg shadow-black">
                                    {{ $promo->title }}
                                </h1>

                                <a href="{{ $promo->link_url ?? $promo->link ?? '#' }}" class="inline-block bg-white text-orbita-blue px-4 py-1.5 md:px-10 md:py-4 rounded-full font-bold text-[9px] md:text-xs uppercase tracking-widest hover:bg-orbita-gold hover:text-white transition shadow-xl">
                                    {{ $promo->button_text ?? 'Explore Now' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="absolute inset-0 flex items-center justify-center text-white bg-orbita-blue">
                        <p class="font-bold text-xs">Upload Banners in Admin</p>
                    </div>
                @endforelse
            </div>

            {{-- Side Ads - Stays hidden on small mobile screens to keep the landscape look clean --}}
            <div class="hidden lg:block h-full">
                @if($sideAds->count() > 0)
                    <div x-data="{ current: 0, count: {{ $sideAds->count() }} }" 
                         x-init="setInterval(() => { current = (current + 1) % count }, 5000)"
                         class="relative rounded-[2.5rem] overflow-hidden shadow-card border border-white bg-white h-full flex flex-col group">
                        
                        @foreach($sideAds as $index => $ad)
                            <div x-show="current === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-700"
                                 class="absolute inset-0 w-full h-full flex flex-col p-8 items-center text-center bg-orbita-light justify-center">
                                
                                <div class="flex-1 flex items-center justify-center w-full">
                                    @php $adImg = $ad->image_path ?? $ad->image; @endphp
                                    <img src="{{ asset('storage/'.$adImg) }}" class="max-w-[180px] max-h-[220px] object-contain drop-shadow-2xl">
                                </div>

                                <div class="mt-6 w-full">
                                    <h3 class="text-xl font-black text-orbita-blue uppercase mb-2">{{ $ad->title }}</h3>
                                    <a href="{{ $ad->link_url ?? $ad->link ?? '#' }}" class="block w-full py-3 bg-orbita-blue text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-orbita-gold transition shadow-lg">
                                        {{ $ad->button_text ?? 'View Deal' }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- 2. CLIENTS MARQUEE --}}
    <section class="py-28 relative bg-white overflow-hidden border-b border-gray-100">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-gray-100 rounded-full blur-3xl opacity-60"></div>
            <div class="absolute top-1/2 right-0 w-[500px] h-[500px] bg-orbita-gold/5 rounded-full blur-[100px] translate-x-1/2"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10 text-center mb-24">
            <span class="text-orbita-gold font-bold uppercase tracking-[0.3em] text-sm mb-4 block animate-pulse-slow">
                Our Ecosystem
            </span>
            <h2 class="text-5xl md:text-7xl font-black text-orbita-blue mb-8 tracking-tighter leading-tight">
                Trusted by <br class="hidden md:block"> Industry Leaders
            </h2>
            <p class="text-gray-500 max-w-3xl mx-auto text-xl leading-relaxed font-medium">
                We are proud to secure and empower the most prestigious hospitality brands across Kenya and East Africa with our smart technology.
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
                                 alt="Client Logo">
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
        <div class="bg-orbita-blue rounded-t-[2.5rem] p-5 md:px-10 flex flex-col md:flex-row justify-between items-center gap-4 border-b border-white/10 shadow-lg">
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
                    View All
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

   {{-- 4. COLLABORATE CTA - FORCED DARK BACKGROUND --}}
    <section class="py-24 container mx-auto px-4">
        {{-- Added 'important' inline style to prevent layout overrides --}}
        <div class="relative rounded-[3rem] md:rounded-[4.5rem] overflow-hidden shadow-2xl border border-white/10" 
             style="background-color: #021256 !important;">
            
            {{-- Creative Abstract Layers --}}
            <div class="absolute inset-0 z-0 pointer-events-none">
                {{-- Deep Blue Glow --}}
                <div class="absolute top-[-10%] right-[-5%] w-[60%] h-[80%] bg-orbita-blue/20 rounded-full blur-[120px]"></div>
                {{-- Subtle Gold Accent Glow --}}
                <div class="absolute bottom-[-10%] left-[-5%] w-[40%] h-[60%] bg-orbita-gold/10 rounded-full blur-[100px]"></div>
                
                {{-- Classy Tech Grid Overlay --}}
                <div class="absolute inset-0 opacity-[0.1]" 
                     style="background-image: radial-gradient(circle, #d8aa3f 1px, transparent 1px); background-size: 40px 40px;">
                </div>
            </div>

            {{-- Content Container --}}
            <div class="relative z-10 px-6 py-20 md:p-32 text-center">
                <div class="max-w-4xl mx-auto">
                    
                    <span class="inline-block px-5 py-1.5 mb-10 border border-orbita-gold/30 bg-orbita-gold/5 text-orbita-gold text-[9px] md:text-xs font-black uppercase tracking-[0.5em] rounded-full">
                        Premium Hotel Security
                    </span>

                    {{-- High-Contrast Heading --}}
                    <h2 class="text-white text-3xl md:text-7xl font-black mb-10 leading-[1.1] uppercase tracking-tighter">
                        Innovating <br class="hidden md:block"> 
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orbita-gold via-yellow-400 to-orbita-gold">Hospitality Tech</span> 
                        Solutions.
                    </h2>

                    <p class="text-gray-400 text-sm md:text-xl font-medium max-w-2xl mx-auto mb-14 leading-relaxed">
                        Join the elite hospitality brands across East Africa leveraging Orbita's intelligent security and world-class hardware.
                    </p>

                    {{-- Button Group: Classy & Compact --}}
                    <div class="flex flex-wrap justify-center items-center gap-6">
                        {{-- Primary CTA --}}
                        <a href="{{ route('contact') }}" class="w-max">
                            <button class="px-10 md:px-14 py-4 md:py-6 bg-orbita-gold text-white font-black uppercase tracking-widest text-[10px] md:text-xs rounded-full hover:scale-105 transition-all duration-300 shadow-2xl shadow-orbita-gold/30">
                                Get a Quote
                            </button>
                        </a>

                        {{-- Secondary CTA --}}
                        <a href="#" class="w-max">
                            <button class="px-10 md:px-14 py-4 md:py-6 bg-white/5 backdrop-blur-xl border border-white/10 text-white font-black uppercase tracking-widest text-[10px] md:text-xs rounded-full hover:bg-white hover:text-[#020617] transition-all duration-300">
                                View Catalog
                            </button>
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

{{-- 5. PARTNER CTA - LUXURY METALLIC EDITION --}}
    <section class="pb-24 container mx-auto px-4">
        <div class="relative rounded-[3rem] overflow-hidden shadow-2xl border border-orbita-gold/20" 
             style="background: linear-gradient(135deg, #C5A059 0%, #D4AF37 50%, #B8860B 100%) !important;">
            
            {{-- Abstract Geometric Pattern Overlay --}}
            <div class="absolute inset-0 opacity-10 pointer-events-none" 
                 style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
            </div>

            {{-- Glassmorphism Glows --}}
            <div class="absolute top-0 left-0 w-full h-full">
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/20 rounded-full blur-[100px]"></div>
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-black/10 rounded-full blur-[80px]"></div>
            </div>

            <div class="relative z-10 p-10 md:p-20 flex flex-col md:flex-row items-center justify-between gap-12">
                
                <div class="text-center md:text-left text-white max-w-2xl">
                    {{-- Small Badge --}}
                    <div class="inline-flex items-center gap-2 px-3 py-1 mb-6 bg-black/10 rounded-full border border-white/20">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        <span class="text-[9px] font-black uppercase tracking-widest">Authorized Reseller Program</span>
                    </div>

                    <h2 class="text-4xl md:text-6xl font-black uppercase tracking-tighter mb-6 leading-none">
                        Partner <br class="hidden md:block"> With <span class="text-black/40">Orbita.</span>
                    </h2>
                    
                    <p class="text-sm md:text-lg font-bold text-white/90 max-w-md leading-relaxed">
                        Scale your business by becoming a certified reseller. Access exclusive wholesale pricing and priority technical support.
                    </p>
                </div>

                {{-- CTA Button --}}
                <div class="relative w-max">
                    <a href="{{ route('contact') }}" class="group block">
                        {{-- Button Shadow Glow --}}
                        <div class="absolute -inset-1 bg-black/20 rounded-full blur-xl group-hover:bg-white/20 transition duration-500"></div>
                        
                        <button class="relative bg-orbita-blue text-white px-10 md:px-14 py-5 rounded-full font-black uppercase tracking-[0.2em] text-[10px] md:text-xs hover:bg-white hover:text-orbita-blue transition-all duration-300 shadow-2xl flex items-center gap-3">
                            Join the Network
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin=\"round\" stroke-width=\"3\" d=\"M13 7l5 5m0 0l-5 5m5-5H6\"/></svg>
                        </button>
                    </a>
                </div>

            </div>

            {{-- Subtle Bottom Text Decor --}}
            <div class="absolute -bottom-4 left-10 opacity-[0.05] pointer-events-none select-none">
                <h3 class="text-8xl font-black text-white italic">PARTNERSHIP</h3>
            </div>
        </div>
    </section>

    {{-- 6. TESTIMONIALS --}}
    <section class="py-28 relative bg-white overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-gray-50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-20">
                <span class="text-orbita-gold font-bold uppercase tracking-[0.3em] text-xs mb-4 block">Success Stories</span>
                <h2 class="text-4xl md:text-6xl font-black text-orbita-blue tracking-tighter">What Our Partners Say</h2>
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
                                    <img src="{{ asset('storage/' . $testimonial->image_path) }}" class="w-full h-full object-cover" alt="{{ $testimonial->client_name }}">
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

    {{-- 7. WELCOME MODAL --}}
    <div x-data="{ show: true }" x-show="show" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center px-4 backdrop-blur-sm bg-orbita-blue/60">
        <div class="bg-white rounded-[2rem] shadow-2xl max-w-md w-full p-2 relative overflow-hidden animate-float">
            <button @click="show = false" class="absolute top-4 right-4 z-20 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition">×</button>
            <div class="p-10 text-center bg-orbita-light rounded-[1.5rem] border border-gray-100">
                <div class="w-16 h-16 bg-orbita-blue text-white rounded-2xl flex items-center justify-center text-2xl font-black mx-auto mb-6 shadow-glow">O</div>
                <h2 class="text-2xl font-black text-orbita-blue uppercase mb-2">Welcome!</h2>
                <p class="text-gray-500 text-sm mb-6">Join our exclusive list for hotel managers.</p>
                <form class="space-y-3">
                    <input type="email" placeholder="Work Email Address" class="w-full px-4 py-3 rounded-xl border-none bg-white shadow-sm focus:ring-2 focus:ring-orbita-gold text-sm text-center">
                    <button class="w-full py-3 bg-orbita-blue text-white font-bold rounded-xl hover:bg-orbita-gold transition shadow-lg uppercase text-xs tracking-widest">Subscribe</button>
                </form>
            </div>
        </div>
    </div>

@endsection