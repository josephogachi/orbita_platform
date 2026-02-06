<x-app-layout>
    <div class="bg-white min-h-screen font-['Plus_Jakarta_Sans',_sans-serif]">
        
        {{-- HERO SECTION --}}
        <div class="relative bg-orbita-blue py-24 md:py-32 overflow-hidden">
            {{-- Background Pattern --}}
            <div class="absolute inset-0 opacity-10" 
                 style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');">
            </div>
            
            <div class="relative max-w-7xl mx-auto px-4 text-center z-10">
                <span class="text-orbita-gold text-[10px] font-black uppercase tracking-[0.3em] mb-4 block">
                    Established Excellence
                </span>
                <h1 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter mb-6">
                    We Are <span class="text-orbita-gold">Orbita Kenya</span>
                </h1>
                <p class="text-gray-300 max-w-2xl mx-auto text-lg leading-relaxed font-medium">
                    The leading provider of hospitality security solutions in East Africa. We combine global manufacturing standards with local expertise to secure the region's finest properties.
                </p>
            </div>
        </div>

        {{-- MISSION & VISION GRID --}}
        <div class="max-w-7xl mx-auto px-4 -mt-16 relative z-20 mb-24">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                
                {{-- Card 1 --}}
                <div class="bg-white p-10 rounded-[2.5rem] shadow-xl border border-gray-100 hover:-translate-y-2 transition-transform duration-500">
                    <div class="w-16 h-16 bg-orbita-blue/5 rounded-full flex items-center justify-center mx-auto mb-6 text-orbita-blue">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-orbita-blue uppercase mb-3 tracking-tight">Our Mission</h3>
                    <p class="text-gray-500 text-sm leading-relaxed font-medium">
                        To modernize the East African hospitality industry by providing world-class locking systems that guarantee security without compromising on luxury.
                    </p>
                </div>

                {{-- Card 2 (Gold - Featured) --}}
                <div class="bg-orbita-blue p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden transform md:-translate-y-4">
                    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#CCA43B 1px, transparent 1px); background-size: 20px 20px;"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6 text-orbita-gold">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-white uppercase mb-3 tracking-tight">Quality Promise</h3>
                        <p class="text-gray-300 text-sm leading-relaxed font-medium">
                            Every Orbita product undergoes rigorous 46-step quality testing. We install peace of mind backed by a 2-year factory warranty.
                        </p>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div class="bg-white p-10 rounded-[2.5rem] shadow-xl border border-gray-100 hover:-translate-y-2 transition-transform duration-500">
                    <div class="w-16 h-16 bg-orbita-blue/5 rounded-full flex items-center justify-center mx-auto mb-6 text-orbita-blue">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-orbita-blue uppercase mb-3 tracking-tight">Our Reach</h3>
                    <p class="text-gray-500 text-sm leading-relaxed font-medium">
                        Proudly serving Kenya, Uganda, Tanzania, and Rwanda from our strategic hubs in Nairobi's vibrant Eastleigh district.
                    </p>
                </div>
            </div>
        </div>

        {{-- STORY SECTION --}}
        <div class="max-w-7xl mx-auto px-4 pb-24 grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div class="space-y-6">
                <span class="text-orbita-gold font-black uppercase tracking-widest text-xs">The Orbita Difference</span>
                <h2 class="text-4xl font-black text-orbita-blue uppercase leading-none">
                    Excellence in <br>Eastleigh.
                </h2>
                <div class="prose prose-lg text-gray-600 font-medium">
                    <p>
                        Orbita Kenya operates from the heart of Nairobi. Our <strong>Main Offices</strong> are located at the <strong>Decale Palace Hotel, 2nd Floor</strong>, while our flagship <strong>Showroom</strong> is situated at the <strong>BBS Mall on General Wairunge Street</strong>.
                    </p>
                    <p>
                        We understood early on that a hotel lock is not just hardware; it is the first touchpoint of a guest's experience. This is why we provide end-to-end support, from technical consultation to factory-trained installations.
                    </p>
                </div>
                
                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-6 pt-6 border-t border-gray-100">
                    <div>
                        <span class="block text-3xl font-black text-orbita-blue">15+</span>
                        <span class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Years Experience</span>
                    </div>
                    <div>
                        <span class="block text-3xl font-black text-orbita-blue">500+</span>
                        <span class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Projects Done</span>
                    </div>
                    <div>
                        <span class="block text-3xl font-black text-orbita-blue">10k+</span>
                        <span class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Rooms Secured</span>
                    </div>
                </div>
            </div>
            
            <div class="relative">
    <div class="absolute inset-0 bg-orbita-gold rounded-[3rem] transform rotate-3 translate-x-4 translate-y-4"></div>
    
    @if(isset($settings) && $settings->about_image_path)
        {{-- This looks into the public/storage folder --}}
        <img src="{{ asset('storage/' . $settings->about_image_path) }}" 
             class="relative rounded-[3rem] shadow-2xl grayscale hover:grayscale-0 transition-all duration-700 w-full object-cover h-[500px]" 
             alt="Orbita Kenya Team">
    @else
        {{-- Fallback image --}}
        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80" 
             class="relative rounded-[3rem] shadow-2xl grayscale hover:grayscale-0 transition-all duration-700 w-full object-cover h-[500px]" 
             alt="Orbita Office">
    @endif
</div>
        </div>

        {{-- CTA --}}
        <div class="bg-gray-900 py-20 text-center relative overflow-hidden">
             <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(#CCA43B 1px, transparent 1px); background-size: 30px 30px;"></div>
            <h2 class="text-3xl font-black text-white uppercase mb-8 relative z-10">Experience the technology in person</h2>
            <a href="{{ route('contact') }}" class="relative z-10 inline-block bg-orbita-gold text-white px-12 py-4 rounded-full font-black uppercase tracking-widest hover:bg-white hover:text-orbita-blue transition shadow-lg">
                Visit Our Showroom
            </a>
        </div>
    </div>
</x-app-layout>