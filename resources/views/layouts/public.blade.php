<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orbita Kenya | Smart Hospitality Solutions</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
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

// Pass User Data to Chat for personalized support
@auth
    Tawk_API.onLoad = function(){
        Tawk_API.setAttributes({
            'name'  : '{{ auth()->user()->name }}',
            'email' : '{{ auth()->user()->email }}',
            'hash'  : '{{ hash_hmac("sha256", auth()->user()->email, "your_tawk_secret") }}'
        }, function(error){});
    };
@endauth
</script>
<body class="bg-orbita-light text-gray-900 antialiased overflow-x-hidden selection:bg-orbita-gold selection:text-white flex flex-col min-h-screen">

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
            
            @if(isset($settings) && $settings->show_countdown && $settings->countdown_end)
            <div x-data="countdown('{{ $settings->countdown_end }}')" class="flex items-center gap-2 bg-white/10 px-3 py-1 rounded-full">
                <span class="text-orbita-gold font-black uppercase tracking-widest text-[10px]">{{ $settings->promo_banner_text ?? 'OFFER:' }}</span>
                <div class="flex gap-1 font-mono text-white text-[10px]">
                    <span x-text="days">00</span>d <span x-text="hours">00</span>h <span x-text="minutes">00</span>m
                </div>
            </div>
            @endif

            <div class="flex items-center gap-4">
                <a href="/admin" class="hover:text-orbita-gold transition">Staff Login</a>
                <div class="flex gap-2 text-white/80">
                    <button class="hover:text-orbita-gold">EN</button>
                    <span class="opacity-30">|</span>
                    <button class="hover:text-orbita-gold">KES</button>
                </div>
            </div>
        </div>
    </div>

    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100 transition-all duration-300">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            
            <a href="/" class="flex items-center">
                @if(isset($settings) && $settings->logo_path)
                    {{-- BIGGER LOGO: h-14 (Mobile) to h-24 (Desktop) --}}
                    <img src="{{ asset('storage/' . $settings->logo_path) }}" 
                         class="h-14 md:h-24 w-auto object-contain transition-transform duration-300 hover:scale-105" 
                         alt="{{ $settings->shop_name }}">
                @else
                    <div class="leading-tight group">
                        <span class="block text-3xl font-black text-orbita-blue tracking-tighter group-hover:text-orbita-gold transition">ORBITA</span>
                        <span class="block text-xs font-bold text-orbita-gold uppercase tracking-[0.3em] group-hover:text-orbita-blue transition">Kenya</span>
                    </div>
                @endif
            </a>

            <nav class="hidden lg:flex items-center gap-8 font-bold text-xs uppercase tracking-widest text-gray-600">
                <a href="/" class="text-orbita-blue hover:text-orbita-gold py-2 transition-all border-b-2 border-transparent hover:border-orbita-gold">Home</a>
                <a href="#about" class="hover:text-orbita-gold py-2 transition-all border-b-2 border-transparent hover:border-orbita-gold">About</a>
                <a href="#products" class="hover:text-orbita-gold py-2 transition-all border-b-2 border-transparent hover:border-orbita-gold">Products</a>
                <a href="#work" class="hover:text-orbita-gold py-2 transition-all border-b-2 border-transparent hover:border-orbita-gold">Our Work</a>
                <a href="#contact" class="hover:text-orbita-gold py-2 transition-all border-b-2 border-transparent hover:border-orbita-gold">Contact</a>
            </nav>

            <div class="flex items-center gap-4">
                <a href="/admin" class="p-2 text-gray-400 hover:text-orbita-blue transition" title="My Account">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </a>
                <button class="p-2 text-gray-400 hover:text-orbita-blue transition relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-orbita-gold rounded-full border-2 border-white"></span>
                </button>
                <a href="#" class="hidden md:inline-flex bg-orbita-blue text-white px-6 py-3 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-orbita-gold hover:shadow-lg transition transform hover:-translate-y-0.5">
                    Catalog
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

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
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-orbita-gold hover:text-white transition duration-300 text-gray-400">FB</a>
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-orbita-gold hover:text-white transition duration-300 text-gray-400">IG</a>
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-orbita-gold hover:text-white transition duration-300 text-gray-400">LN</a>
                    </div>
                </div>

                <div>
                    <h4 class="text-orbita-gold font-bold uppercase text-xs tracking-[0.2em] mb-8">Corporate</h4>
                    <ul class="space-y-4 text-sm font-medium text-gray-400">
                        <li><a href="#" class="hover:text-white hover:pl-2 transition-all inline-block">About Company</a></li>
                        <li><a href="#" class="hover:text-white hover:pl-2 transition-all inline-block">Our Projects</a></li>
                        <li><a href="#" class="hover:text-white hover:pl-2 transition-all inline-block">Become a Partner</a></li>
                        <li><a href="#" class="hover:text-white hover:pl-2 transition-all inline-block">Contact Sales</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-orbita-gold font-bold uppercase text-xs tracking-[0.2em] mb-8">Support Center</h4>
                    <ul class="space-y-4 text-sm font-medium text-gray-400">
                        <li><a href="#" class="hover:text-white hover:pl-2 transition-all inline-block">Installation Guide</a></li>
                        <li><a href="#" class="hover:text-white hover:pl-2 transition-all inline-block">Software Downloads</a></li>
                        <li><a href="#" class="hover:text-white hover:pl-2 transition-all inline-block">Warranty Policy</a></li>
                        <li><a href="#" class="hover:text-white hover:pl-2 transition-all inline-block">Request Maintenance</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-orbita-gold font-bold uppercase text-xs tracking-[0.2em] mb-8">Stay Connected</h4>
                    <p class="text-gray-400 text-xs mb-6">Subscribe to get exclusive B2B offers and industry news.</p>
                    <form class="relative">
                        <input type="email" placeholder="Email Address" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs focus:ring-1 focus:ring-orbita-gold outline-none text-white">
                        <button class="absolute right-1 top-1 bottom-1 bg-orbita-gold text-white px-4 rounded-lg font-bold text-[10px] uppercase tracking-widest hover:bg-white hover:text-orbita-blue transition">
                            Join
                        </button>
                    </form>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                <p>&copy; {{ date('Y') }} Orbita Kenya. All rights reserved.</p>
                <div class="flex gap-8 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                    <a href="#" class="hover:text-white transition">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function countdown(expiry) {
            return {
                expiry: new Date(expiry).getTime(),
                days: '00', hours: '00', minutes: '00', seconds: '00',
                interval: null,
                init() {
                    this.interval = setInterval(() => {
                        const now = new Date().getTime();
                        const distance = this.expiry - now;
                        if (distance < 0) { clearInterval(this.interval); return; }
                        this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                        this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                        this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                        this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
                    }, 1000);
                }
            }
        }
    </script>
</body>
</html>