<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orbita Kenya | Smart Hospitality Solutions</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-orbita-light text-gray-900 antialiased flex flex-col min-h-screen">

    {{-- 1. TOP BAR --}}
    <div class="bg-orbita-blue text-white text-xs font-semibold relative overflow-hidden border-b border-white/10">
        <div class="container mx-auto px-4 py-2 flex flex-col md:flex-row justify-between items-center gap-2 relative z-10">
            <div class="flex items-center gap-4 text-white/80">
                <span class="flex items-center gap-1 hover:text-orbita-gold transition cursor-pointer">
                    <svg class="w-3 h-3 text-orbita-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg> 
                    {{ $settings->phone_contact ?? '+254 700 000 000' }}
                </span>
                <span class="hidden md:flex items-center gap-1 hover:text-orbita-gold transition cursor-pointer">
                    <svg class="w-3 h-3 text-orbita-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $settings->email_contact ?? 'sales@orbita.co.ke' }}
                </span>
            </div>
            
            {{-- Countdown / Promo Banner Logic --}}
            @if(isset($settings) && $settings->show_countdown && $settings->countdown_end)
            <div x-data="countdown('{{ $settings->countdown_end }}')" class="flex items-center gap-2 bg-white/10 px-3 py-1 rounded-full">
                <span class="text-orbita-gold font-black uppercase tracking-widest text-[10px]">{{ $settings->promo_banner_text ?? 'OFFER:' }}</span>
                <div class="flex gap-1 font-mono text-white text-[10px]">
                    <span x-text="days">00</span>d <span x-text="hours">00</span>h <span x-text="minutes">00</span>m
                </div>
            </div>
            @endif

            <div class="flex items-center gap-4">
                <div class="flex gap-2 text-white/80">
                    <button class="hover:text-orbita-gold">EN</button>
                    <span class="opacity-30">|</span>
                    <button class="hover:text-orbita-gold">KES</button>
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
                        <li><a href="#" class="hover:text-white hover:pl-2 transition-all inline-block">About Company</a></li>
                        <li><a href="#" class="hover:text-white hover:pl-2 transition-all inline-block">Our Projects</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-orbita-gold font-bold uppercase text-xs tracking-[0.2em] mb-8">Support Center</h4>
                    <ul class="space-y-4 text-sm font-medium text-gray-400">
                        <li><a href="#" class="hover:text-white hover:pl-2 transition-all inline-block">Installation Guide</a></li>
                        <li><a href="#" class="hover:text-white hover:pl-2 transition-all inline-block">Warranty Policy</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-orbita-gold font-bold uppercase text-xs tracking-[0.2em] mb-8">Stay Connected</h4>
                    <form class="relative">
                        <input type="email" placeholder="Email Address" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs focus:ring-1 focus:ring-orbita-gold outline-none text-white">
                        <button class="absolute right-1 top-1 bottom-1 bg-orbita-gold text-white px-4 rounded-lg font-bold text-[10px] uppercase tracking-widest hover:bg-white hover:text-orbita-blue transition">Join</button>
                    </form>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                <p>&copy; {{ date('Y') }} Orbita Kenya. All rights reserved.</p>
                
                {{-- HIDDEN STAFF ACCESS --}}
                <div class="my-4 md:my-0">
                    <a href="{{ route('filament.admin.auth.login') }}" class="text-gray-600/30 hover:text-gray-500 transition-colors cursor-default hover:cursor-pointer">
                        System Access
                    </a>
                </div>

                <div class="flex gap-8 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

   {{-- Replace the messy end of your file with this clean version --}}
    </footer>

    {{-- Chat Widget Script --}}
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/YOUR_UNIQUE_ID/default';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
</body>
</html>
    </script>
</body>
</html>
                    </form>
                </div>
            @else
                <div class="px-4">
                    <div class="font-bold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space