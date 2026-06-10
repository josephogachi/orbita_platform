<!DOCTYPE html>
<html lang="en-KE" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- 🎯 DYNAMIC SEO TAGS --}}
    <title>{{ $seo_title ?? 'Orbita Kenya | Smart Hospitality Solutions' }}</title>
    <meta name="description" content="{{ $seo_description ?? 'Leading supplier of premium smart door locks, hotel card locks, and access control systems in Nairobi, Kenya. Expert installation and lifetime support.' }}">
    <meta name="keywords" content="smart locks kenya, digital door locks nairobi, hotel locks kenya, fingerprint locks, Orbita Kenya">
    
    {{-- 🎯 CANONICAL TAG --}}
    <link rel="canonical" href="{{ url()->current() }}" />

    {{-- 🎯 OPEN GRAPH --}}
    <meta property="og:locale" content="en_KE">
    <meta property="og:site_name" content="Orbita Kenya">
    <meta property="og:title" content="{{ $seo_title ?? 'Orbita Kenya | Premium Smart Security' }}">
    <meta property="og:description" content="{{ $seo_description ?? 'Premium Smart Locks and Hospitality Solutions in Kenya' }}">
    <meta property="og:image" content="{{ isset($product) && isset($product->images[0]) ? asset('storage/'.$product->images[0]) : asset('images/default-orbita-share.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="{{ isset($product) ? 'product' : 'website' }}">

    {{-- 🎯 TWITTER CARDS --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo_title ?? 'Orbita Kenya | Premium Smart Security' }}">
    <meta name="twitter:description" content="{{ $seo_description ?? 'Premium Smart Locks and Hospitality Solutions in Kenya' }}">
    <meta name="twitter:image" content="{{ isset($product) && isset($product->images[0]) ? asset('storage/'.$product->images[0]) : asset('images/default-orbita-share.jpg') }}">

    {{-- 🎯 LOCAL BUSINESS SCHEMA (Escaped @@ for Blade compatibility) --}}
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "LocalBusiness",
      "name": "Orbita Kenya",
      "image": "{{ asset('favicon.png') }}",
      "@@id": "{{ url('/') }}",
      "url": "{{ url('/') }}",
      "telephone": "{{ $settings->phone_contact ?? '+254 726 777 733' }}",
      "email": "{{ $settings->email_contact ?? 'info@orbitakenya.com' }}",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Adlife Plaza, Ring Road Kilimani in Nairobi,",
        "addressLocality": "Nairobi",
        "addressRegion": "Nairobi County",
        "postalCode": "00100",
        "addressCountry": "KE"
      },
      "geo": {
        "@@type": "GeoCoordinates",
        "latitude": -1.2774,
        "longitude": 36.8488
      },
      "openingHoursSpecification": {
        "@@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
        ],
        "opens": "09:00",
        "closes": "18:00"
      },
      "sameAs": [
        "{{ url('/') }}"
      ],
      "priceRange": "$$$"
    }
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- INLINE CSS TO GUARANTEE SHIMMER & STYLES --}}
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        [x-cloak] { display: none !important; }

        /* Top Bar Shimmer Animation */
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .animate-shimmer {
            background: linear-gradient(90deg, #dc2626, #f97316, #dc2626) !important;
            background-size: 200% 100% !important;
            animation: shimmer 4s linear infinite !important;
        }
    </style>

    @livewireStyles
    @stack('schema')
</head>

@php $settings = \App\Models\ShopSetting::first(); @endphp

<body class="bg-orbita-light text-gray-900 antialiased flex flex-col min-h-screen">

   {{-- 1. HIGH-END E-COMMERCE TOP BAR --}}
<div class="animate-shimmer text-white text-[10px] md:text-xs font-black relative overflow-hidden shadow-md">
    <div class="container mx-auto px-4 py-2 flex flex-col md:flex-row justify-between items-center gap-3 relative z-10">
        
       {{-- Contact Info --}}
<div class="flex items-center gap-6 text-white/90">
    {{-- Phone --}}
    <a href="tel:{{ $settings->phone_contact ?? '+254 700 000 000' }}" class="flex items-center gap-1.5 hover:scale-105 transition-transform">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
        <span>{{ $settings->phone_contact ?? '+254 700 000 000' }}</span>
    </a>

    {{-- Email --}}
    <a href="mailto:{{ $settings->email_contact ?? 'info@orbitakenya.com' }}" class="flex items-center gap-1.5 hover:scale-105 transition-transform">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <span>{{ $settings->email_contact ?? 'info@orbitakenya.com' }}</span>
    </a>
</div>

        {{-- Animated Promo/Countdown --}}
        @if(isset($settings) && $settings->show_countdown && $settings->countdown_end)
        <div x-data="flashSaleTimer('{{ $settings->countdown_end }}')" class="flex items-center gap-3 bg-black/20 px-4 py-1 rounded-full backdrop-blur-sm border border-white/10">
            <span class="uppercase tracking-widest animate-pulse">{{ $settings->promo_banner_text ?? 'FLASH SALE ENDS IN:' }}</span>
            <div class="flex gap-1.5 font-mono text-[11px]">
                <span class="bg-white text-red-600 px-1.5 py-0.5 rounded shadow-inner" x-text="timeLeft.hours">00</span>:
                <span class="bg-white text-red-600 px-1.5 py-0.5 rounded shadow-inner" x-text="timeLeft.minutes">00</span>:
                <span class="bg-white text-red-600 px-1.5 py-0.5 rounded shadow-inner" x-text="timeLeft.seconds">00</span>
            </div>
        </div>
        @endif

        {{-- Locale Switcher --}}
        <div class="flex items-center gap-4 text-white/90">
            <div class="flex gap-2">
                <button class="hover:text-white transition">EN</button>
                <span class="opacity-30">|</span>
                <button class="hover:text-white transition">KES</button>
            </div>
        </div>
    </div>
</div>

    {{-- 2. NAVIGATION --}}
    @include('layouts.navigation')

    {{-- 3. MAIN CONTENT SLOT --}}
    <main class="flex-grow">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- 4. FOOTER --}}
    <footer class="bg-orbita-dark text-white pt-24 pb-10 relative overflow-hidden mt-auto border-t border-orbita-blue">
        <div class="absolute top-0 left-0 w-96 h-96 bg-orbita-gold/5 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-500/10 rounded-full blur-[100px] translate-x-1/3 translate-y-1/3 pointer-events-none"></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20 border-b border-white/5 pb-16">
                <div>
                    <h2 class="text-3xl font-black uppercase tracking-tighter mb-6">Orbita<span class="text-orbita-gold">.</span></h2>
                    <p class="text-gray-400 text-sm leading-relaxed mb-8">
                        The leading provider of smart hospitality technology in East Africa. Securing hotels with luxury hardware and intelligent software.
                    </p>
                </div>

                <div>
                    <h4 class="text-orbita-gold font-bold uppercase text-xs tracking-[0.2em] mb-8">Corporate</h4>
                    <ul class="space-y-4 text-sm font-medium text-gray-400">
                        <li><a href="{{ route('about') }}" class="hover:text-white hover:pl-2 transition-all inline-block">About Company</a></li>
                        <li><a href="{{ route('work') }}" class="hover:text-white hover:pl-2 transition-all inline-block">Our Projects</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-orbita-gold font-bold uppercase text-xs tracking-[0.2em] mb-8">Support Center</h4>
                    <ul class="space-y-4 text-sm font-medium text-gray-400">
                        <li><a href="{{ route('policy.installation') }}" class="hover:text-white hover:pl-2 transition-all inline-block">Installation Guide</a></li>
                        <li><a href="{{ route('policy.warranty') }}" class="hover:text-white hover:pl-2 transition-all inline-block">Warranty Policy</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-orbita-gold font-bold uppercase text-xs tracking-[0.2em] mb-8">Stay Connected</h4>
                    <form action="{{ route('subscribe.store') }}" method="POST" class="relative">
                        @csrf
                        <input type="email" name="email" required placeholder="Email Address" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs focus:ring-1 focus:ring-orbita-gold outline-none text-white transition-all">
                        <button type="submit" class="absolute right-1 top-1 bottom-1 bg-orbita-gold text-white px-4 rounded-lg font-bold text-[10px] uppercase tracking-widest hover:bg-white hover:text-orbita-blue transition">Join</button>
                    </form>
                    <p class="text-[9px] text-gray-500 mt-2 uppercase tracking-tighter">Join the elite hotel managers list</p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                <p>&copy; {{ date('Y') }} Orbita Kenya. All rights reserved.</p>
                
                <div class="my-4 md:my-0">
                    <a href="{{ route('filament.admin.auth.login') }}" class="text-gray-600/30 hover:text-orbita-gold/50 transition-colors cursor-default hover:cursor-pointer">
                        System Access
                    </a>
                </div>

                <div class="flex gap-8 mt-4 md:mt-0">
                    <a href="{{ route('policy.privacy') }}" class="hover:text-white transition">Privacy Policy</a>
                    <a href="{{ route('policy.terms') }}" class="hover:text-white transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
    {{-- Chat Widget Script --}}
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/679f72c03bd1c46011a681cc/1ijk6fbe5';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
</body>
</html>