<div class="min-h-screen bg-orbita-light flex flex-col font-['Plus_Jakarta_Sans',_sans-serif]">
    {{-- 1. INTERNAL CSS & SETTINGS --}}
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        input:focus, textarea:focus, select:focus { outline: none !important; box-shadow: 0 0 0 2px #CCA43B !important; }
        .map-container { filter: grayscale(100%); transition: all 0.5s ease; }
        .map-container:hover { filter: grayscale(0%); }
    </style>
    
    @php 
        $settings = \App\Models\ShopSetting::first(); 
    @endphp

    {{-- 2. TOP BAR --}}
    <div class="bg-orbita-blue text-white text-[10px] font-bold uppercase tracking-widest border-b border-white/10">
        <div class="container mx-auto px-4 py-2 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <span class="flex items-center gap-2">
                    <svg class="w-3 h-3 text-orbita-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ $settings->phone_contact ?? '+254 700 000 000' }}
                </span>
                <span class="hidden md:flex items-center gap-2">
                    <svg class="w-3 h-3 text-orbita-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $settings->email_contact ?? 'sales@orbita.co.ke' }}
                </span>
            </div>
            <div class="flex gap-4">
                <span>EN</span>
                <span class="opacity-20">|</span>
                <span>KES</span>
            </div>
        </div>
    </div>

    {{-- 3. NAVIGATION --}}
    @include('layouts.navigation')

    {{-- 4. CONTACT HERO --}}
    <div class="bg-orbita-blue py-24 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#CCA43B 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="relative z-10 container mx-auto px-4">
            <h1 class="text-5xl md:text-7xl font-black text-white uppercase tracking-tighter">
                Get in <span class="text-orbita-gold">Touch</span>
            </h1>
            <p class="text-gray-300 mt-6 max-w-xl mx-auto text-lg font-medium">
                Hospitality Security Experts at your service. Visit our showrooms or reach out for a technical consultation.
            </p>
        </div>
    </div>

    {{-- 5. CONTACT FORM & INFO --}}
    <div class="container mx-auto px-4 py-16 -mt-16 relative z-20 flex-grow">
        <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-gray-100 grid grid-cols-1 lg:grid-cols-2">
            
            {{-- Info Panel --}}
            <div class="bg-orbita-blue text-white p-12 md:p-16 flex flex-col justify-between relative">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-orbita-gold rounded-full opacity-10 blur-3xl"></div>
                <div class="space-y-12 relative z-10">
                    <div>
                        <h3 class="text-orbita-gold font-black uppercase tracking-widest text-[10px] mb-4">Our Locations</h3>
                        <h2 class="text-3xl font-bold leading-tight uppercase">Orbita Kenya</h2>
                    </div>
                    
                    <div class="space-y-8">
                        {{-- Office --}}
                        <div class="flex items-start gap-4">
                            <div class="bg-white/10 p-4 rounded-2xl text-orbita-gold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-lg text-orbita-gold">Main Offices</p>
                                <p class="text-gray-300 text-sm">Decale Palace Hotel, 2nd Floor<br>Eastleigh, Nairobi</p>
                            </div>
                        </div>

                        {{-- Showroom --}}
                        <div class="flex items-start gap-4">
                            <div class="bg-white/10 p-4 rounded-2xl text-orbita-gold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-lg text-orbita-gold">Showroom</p>
                                <p class="text-gray-300 text-sm">BBS Mall, General Wairunge Street<br>Eastleigh, Nairobi</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <a href="#" title="Facebook" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-orbita-gold transition">FB</a>
                        <a href="#" title="Instagram" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-orbita-gold transition">IG</a>
                        <a href="#" title="TikTok" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-orbita-gold transition">TK</a>
                    </div>
                </div>
            </div>

            {{-- Form Panel --}}
            <div class="p-12 md:p-16 bg-white">
                <form wire:submit.prevent="submit" class="space-y-6">
                    @if (session()->has('success'))
                        <div class="bg-green-50 text-green-800 p-4 rounded-xl font-bold text-sm border border-green-100">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="text" wire:model="name" placeholder="Full Name" class="bg-gray-50 border-none rounded-xl p-5 text-sm">
                        <input type="text" wire:model="phone" placeholder="Phone (e.g. 07xx...)" class="bg-gray-50 border-none rounded-xl p-5 text-sm">
                    </div>
                    
                    <input type="email" wire:model="email" placeholder="Email Address" class="w-full bg-gray-50 border-none rounded-xl p-5 text-sm">
                    
                    <select wire:model="subject" class="w-full bg-gray-50 border-none rounded-xl p-5 text-sm text-gray-500">
                        <option value="">Select Inquiry Subject</option>
                        <option value="Hotel Lock Systems">Hotel Lock Systems</option>
                        <option value="Minibars & Safes">Minibars & Safes</option>
                        <option value="Technical Support">Technical Support</option>
                        <option value="Bulk Quotation">Bulk Quotation / Project</option>
                        <option value="After Sales Service">After Sales Service</option>
                        <option value="Other">Other Inquiry</option>
                    </select>

                    <textarea wire:model="message" rows="5" placeholder="How can we help you?" class="w-full bg-gray-50 border-none rounded-xl p-5 text-sm"></textarea>

                    <button type="submit" class="w-full bg-orbita-gold text-white py-5 rounded-xl font-black uppercase tracking-[0.2em] text-[10px] hover:bg-orbita-blue transition shadow-xl">
                        <span wire:loading.remove>Submit Inquiry</span>
                        <span wire:loading>Sending Inquiry...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- 6. RESPONSIVE DUAL MAPS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 w-full h-[500px] border-t border-gray-200">
        {{-- Map 1: BBS Mall Showroom --}}
        <div class="relative group h-full map-container">
            <div class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur p-3 rounded-lg shadow-sm border border-gray-100">
                <p class="text-[10px] font-black uppercase text-orbita-blue">Visit Showroom</p>
                <p class="text-xs font-bold">BBS Mall, Eastleigh</p>
            </div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.823485573425!2d36.8453488!3d-1.2795287!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f171016833989%3A0x7d6b38c35d96201b!2sBBS%20Mall!5e0!3m2!1sen!2ske!4v1700000000000" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
        
        {{-- Map 2: Decale Palace Offices --}}
        <div class="relative group h-full map-container border-l border-white/20">
            <div class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur p-3 rounded-lg shadow-sm border border-gray-100">
                <p class="text-[10px] font-black uppercase text-orbita-blue">Main Offices</p>
                <p class="text-xs font-bold">Decale Palace Hotel</p>
            </div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.8252814845577!2d36.8480352!3d-1.2783701!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f1737e19d7d31%3A0x3b1106e5d590403!2sDecale%20Palace%20Hotel!5e0!3m2!1sen!2ske!4v1700000000000" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>

    {{-- 7. FOOTER --}}
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
                        <li><a href="{{ route('about') }}" class="hover:text-white transition">About Company</a></li>
                        <li><a href="{{ route('work') }}" class="hover:text-white transition">Our Projects</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-orbita-gold font-bold uppercase text-xs tracking-[0.2em] mb-8">Support Center</h4>
                    <ul class="space-y-4 text-sm font-medium text-gray-400">
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white transition">Warranty Policy</a></li>
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
                <p>© {{ date('Y') }} {{ $settings->shop_name ?? 'Orbita Kenya' }}. All rights reserved.</p>
                <div class="my-4 md:my-0">
                    <a href="/admin" class="text-gray-600/30 hover:text-gray-500 transition-colors cursor-default hover:cursor-pointer">System Access</a>
                </div>
                <div class="flex gap-8 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</div>