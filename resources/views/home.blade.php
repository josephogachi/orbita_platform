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
                        <p>Upload Banners in Admin Panel</p>
                    </div>
                @endforelse
            </div>

            <div x-data="{ show: true }" x-show="show" class="hidden lg:flex flex-col relative rounded-[2.5rem] overflow-hidden shadow-card border border-white bg-white">
                <button @click="show = false" class="absolute top-4 right-4 z-20 bg-gray-100 hover:bg-red-50 hover:text-red-500 p-2 rounded-full transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                <div class="h-full flex flex-col p-8 items-center text-center relative bg-orbita-light justify-center">
                    <span class="text-orbita-blue font-black uppercase tracking-[0.2em] text-[10px] mb-6">Trending</span>
                    <img src="https://images.unsplash.com/photo-1558002038-1091a166111c?auto=format&fit=crop&w=400" class="w-48 object-contain drop-shadow-2xl mb-6 transform hover:scale-110 transition duration-500">
                    <h3 class="text-xl font-black text-orbita-blue uppercase leading-none mb-2">Fingerprint <br>Access</h3>
                    <a href="#" class="mt-4 px-8 py-3 bg-orbita-blue text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-orbita-gold transition shadow-lg">View Deal</a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white border-b border-gray-50 overflow-hidden">
        <div class="container mx-auto px-4 text-center mb-12">
            <h3 class="text-[10px] font-black text-gray-300 uppercase tracking-[0.5em]">Trusted By Industry Leaders</h3>
        </div>
        <div class="flex animate-marquee gap-24 items-center whitespace-nowrap">
            @for($i=0; $i<2; $i++)
                @foreach($clients as $client)
                    <div class="flex-shrink-0 group flex flex-col items-center justify-center w-64">
                        <img src="{{ asset('storage/'.$client->logo_path) }}" 
                             class="h-20 w-auto object-contain grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition duration-500 transform hover:scale-110">
                    </div>
                @endforeach
            @endfor
        </div>
    </section>

    <section class="py-28 relative bg-orbita-light overflow-hidden" x-data="{ tab: 'new' }">
        
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-white rounded-full blur-[120px] opacity-70 pointer-events-none translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-orbita-gold/10 rounded-full blur-[100px] opacity-70 pointer-events-none -translate-x-1/2 translate-y-1/2"></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
                <div>
                    <span class="text-orbita-gold font-bold uppercase tracking-widest text-xs mb-3 block">Catalog</span>
                    <h2 class="text-4xl md:text-5xl font-black text-orbita-blue tracking-tight">Curated <br>Excellence</h2>
                </div>
                
                <div class="flex bg-white p-1.5 rounded-full shadow-sm border border-gray-100">
                    <button @click="tab = 'new'" :class="{ 'bg-orbita-blue text-white shadow-md': tab === 'new', 'text-gray-400 hover:text-orbita-blue': tab !== 'new' }" class="px-8 py-3 rounded-full font-black text-[10px] uppercase tracking-widest transition-all">New Arrivals</button>
                    <button @click="tab = 'hot'" :class="{ 'bg-orbita-blue text-white shadow-md': tab === 'hot', 'text-gray-400 hover:text-orbita-blue': tab !== 'hot' }" class="px-8 py-3 rounded-full font-black text-[10px] uppercase tracking-widest transition-all">Hot Selling</button>
                    <button @click="tab = 'sponsored'" :class="{ 'bg-orbita-blue text-white shadow-md': tab === 'sponsored', 'text-gray-400 hover:text-orbita-blue': tab !== 'sponsored' }" class="px-8 py-3 rounded-full font-black text-[10px] uppercase tracking-widest transition-all">Sponsored</button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                @foreach($featuredProducts as $product)
                    <div x-show="(tab === 'new') || (tab === 'hot' && {{ $product->is_hot ? 'true' : 'false' }}) || (tab === 'sponsored' && {{ $product->is_sponsored ? 'true' : 'false' }})"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="group bg-white rounded-[2.5rem] p-6 shadow-card hover:shadow-2xl transition-all duration-500 border border-white flex flex-col relative overflow-hidden h-full">
                        
                        @if($product->discount_percent)
                        <div class="absolute top-0 right-0 bg-orbita-gold text-white px-6 py-3 rounded-bl-[2rem] font-black text-xs z-20 shadow-sm">
                            -{{ $product->discount_percent }}%
                        </div>
                        @endif

                        <div class="h-64 rounded-[2rem] bg-gray-50 mb-6 relative flex items-center justify-center overflow-hidden">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ asset('storage/'.$product->images[0]) }}" class="max-h-48 w-auto object-contain mix-blend-multiply group-hover:scale-110 transition duration-700">
                            @else
                                <div class="flex flex-col items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-[10px] font-bold uppercase tracking-widest">No Image</span>
                                </div>
                            @endif
                            
                            <div class="absolute inset-0 bg-orbita-blue/10 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <button class="bg-white text-orbita-blue px-6 py-3 rounded-full font-bold text-xs uppercase shadow-xl transform translate-y-4 group-hover:translate-y-0 transition duration-300">
                                    Quick View
                                </button>
                            </div>
                        </div>

                        <div class="px-2 pb-2 flex-1 flex flex-col">
                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                                {{ $product->category->name ?? 'Product' }}
                            </div>
                            <h3 class="text-lg font-black text-orbita-blue uppercase leading-tight mb-2 group-hover:text-orbita-gold transition line-clamp-2">{{ $product->name }}</h3>
                            <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                                <span class="text-2xl font-black text-orbita-blue tracking-tighter">KES {{ number_format($product->price) }}</span>
                                <button class="w-10 h-10 rounded-full bg-orbita-blue text-white flex items-center justify-center hover:bg-orbita-gold transition shadow-lg transform group-hover:rotate-90">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

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

    <div x-data="{ show: true }" x-show="show" x-cloak 
         class="fixed inset-0 z-[100] flex items-center justify-center px-4 backdrop-blur-sm bg-orbita-blue/60">
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