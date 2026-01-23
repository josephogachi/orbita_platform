@extends('layouts.public')

@section('content')

    <section class="pt-6 pb-12 px-4 container mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-auto lg:h-[680px]">
            
            <div x-data="{ active: 0, total: {{ $promotions->count() }} }" 
                 x-init="setInterval(() => { active = (active + 1) % total }, 6000)"
                 class="lg:col-span-3 relative rounded-[2.5rem] overflow-hidden shadow-2xl group border-4 border-white bg-gray-900">
                
                @forelse($promotions as $index => $promo)
                    <div x-show="active === {{ $index }}"
                         x-transition:enter="transition transform duration-1000"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute inset-0 w-full h-full">
                        @if($promo->type === 'video')
                            <video class="w-full h-full object-cover" autoplay muted loop playsinline>
                                <source src="{{ asset('storage/'.$promo->file_path) }}" type="video/mp4">
                            </video>
                        @else
                            <img src="{{ asset('storage/'.$promo->file_path) }}" class="w-full h-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-r from-orbita-blue/90 via-orbita-blue/30 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center px-12 md:px-20">
                            <div class="max-w-xl text-white space-y-6">
                                <span class="bg-orbita-gold text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-glow inline-block">Official Partner</span>
                                <h1 class="text-5xl md:text-7xl font-black leading-none uppercase drop-shadow-lg">{{ $promo->title }}</h1>
                                <a href="{{ $promo->link_url ?? '#' }}" class="inline-block bg-white text-orbita-blue px-10 py-4 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-orbita-gold hover:text-white transition shadow-xl">Explore Now</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="absolute inset-0 flex items-center justify-center text-white bg-orbita-blue">
                        <p class="font-bold">Upload Banners in Admin Panel</p>
                    </div>
                @endforelse
            </div>

            <div class="hidden lg:block h-full">
                @if($sideAds->count() > 0)
                    <div x-data="{ current: 0, count: {{ $sideAds->count() }} }" 
                         x-init="setInterval(() => { current = (current + 1) % count }, 5000)"
                         class="relative rounded-[2.5rem] overflow-hidden shadow-card border border-white bg-white h-full flex flex-col group">
                        
                        <div class="absolute top-4 right-4 z-20 bg-gray-100 hover:bg-orbita-gold hover:text-white p-2 rounded-full transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>

                        @foreach($sideAds as $index => $ad)
                            <div x-show="current === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-700"
                                 x-transition:enter-start="opacity-0 translate-y-4"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute inset-0 w-full h-full flex flex-col p-8 items-center text-center bg-orbita-light justify-center">
                                
                                @if($ad->badge_text)
                                    <span class="text-orbita-blue font-black uppercase tracking-[0.2em] text-[10px] mb-6 bg-orbita-gold/10 px-4 py-1 rounded-full animate-pulse-slow">
                                        {{ $ad->badge_text }}
                                    </span>
                                @endif

                                <div class="flex-1 flex items-center justify-center w-full">
                                    <img src="{{ asset('storage/'.$ad->image_path) }}" 
                                         class="max-w-[180px] max-h-[220px] object-contain drop-shadow-2xl transform hover:scale-110 transition duration-700"
                                         alt="{{ $ad->title }}">
                                </div>

                                <div class="mt-6 w-full">
                                    <h3 class="text-xl font-black text-orbita-blue uppercase leading-none mb-2">{{ $ad->title }}</h3>
                                    @if($ad->subtitle)
                                        <p class="text-xs text-gray-500 font-bold mb-4">{{ $ad->subtitle }}</p>
                                    @endif
                                    <a href="{{ $ad->link_url ?? '#' }}" class="block w-full py-3 bg-orbita-blue text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-orbita-gold transition shadow-lg">
                                        {{ $ad->button_text }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($sideAds->count() > 1)
                        <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-20">
                            @foreach($sideAds as $index => $ad)
                                <button @click="current = {{ $index }}" 
                                        :class="{'bg-orbita-blue w-4': current === {{ $index }}, 'bg-gray-300 w-2': current !== {{ $index }}}" 
                                        class="h-2 rounded-full transition-all duration-300"></button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                @else
                    <div class="h-full rounded-[2.5rem] border-2 border-dashed border-gray-200 bg-gray-50 flex flex-col items-center justify-center text-gray-400 p-8 text-center">
                        <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Ad Space Available</span>
                        <span class="text-[9px] mt-1">Add 'SideAds' in Admin</span>
                    </div>
                @endif
            </div>
        </div>
    </section>
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
    <section class="py-12 container mx-auto px-4">
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
                <a href="#" class="bg-white/10 hover:bg-orbita-gold text-white px-5 py-2.5 rounded-full font-bold text-[10px] uppercase tracking-widest transition-all border border-white/20 flex items-center gap-2">
                    View All <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-b-[2.5rem] p-8 shadow-2xl border-x border-b border-gray-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">
            @foreach($section['products'] as $product)
            <div class="group flex flex-col relative bg-white h-full">
                
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
                                // Visual logic: we assume 100 is "full stock" for the bar width
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
    </section>
    @endif
@endforeach
    <section class="py-20 container mx-auto px-4">
        <div class="bg-orbita-blue rounded-[3.5rem] p-16 md:p-24 relative overflow-hidden text-center shadow-3xl">
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="w-full h-full"><path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" /></svg>
            </div>
            <div class="relative z-10 max-w-3xl mx-auto">
                <h2 class="text-white text-4xl md:text-6xl font-black mb-10 leading-tight uppercase italic tracking-tighter">Have a Project? <br>Let's <span class="text-transparent bg-clip-text bg-gradient-to-r from-orbita-gold to-yellow-200">Collaborate.</span></h2>
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="#" class="bg-white text-orbita-blue px-10 py-5 rounded-full font-black uppercase tracking-widest text-xs hover:bg-orbita-gold hover:text-white transition shadow-xl transform hover:scale-105">Download App</a>
                    <a href="#" class="bg-transparent border-2 border-white/20 text-white px-10 py-5 rounded-full font-black uppercase tracking-widest text-xs hover:bg-white hover:text-orbita-blue transition shadow-xl transform hover:scale-105">Book Quotation</a>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-24 container mx-auto px-4">
        <div class="bg-orbita-gold rounded-[3rem] p-12 md:p-20 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-12 shadow-3xl">
             <div class="relative z-10 text-center md:text-left text-white">
                <h2 class="text-4xl font-black uppercase tracking-tighter mb-4">Partner With Us</h2>
                <p class="font-bold opacity-90 max-w-md">Become a certified reseller and access wholesale pricing for smart hotel hardware.</p>
            </div>
            <div class="relative z-10">
                <a href="#" class="bg-orbita-blue text-white px-12 py-5 rounded-full font-black uppercase tracking-[0.2em] text-xs hover:bg-white hover:text-orbita-blue transition shadow-xl inline-block">Join Network</a>
            </div>
        </div>
    </section>
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