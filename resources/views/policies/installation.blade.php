<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-24">
        <span class="text-orbita-gold font-bold uppercase tracking-widest text-xs mb-4 block">Technical Support</span>
        <h1 class="text-5xl font-black text-orbita-blue mb-10 tracking-tighter uppercase">Installation Guide</h1>

        {{-- 📥 PDF DOWNLOAD CARD --}}
        <div class="bg-[#021256] rounded-[2.5rem] p-8 md:p-12 mb-16 text-white flex flex-col md:flex-row items-center justify-between gap-8 shadow-2xl border border-white/10 relative overflow-hidden">
             {{-- Abstract background decor --}}
            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#D4AF37 1px, transparent 1px); background-size: 30px 30px;"></div>
            
            <div class="relative z-10">
                <h2 class="text-3xl font-bold mb-2">Technical Catalog</h2>
                <p class="text-gray-400 max-w-md">Download the complete technical specification and installation manual for all Orbita Smart Lock models (PDF).</p>
            </div>

            <a href="{{ route('catalog.download') }}" 
               class="relative z-10 inline-flex items-center gap-3 bg-orbita-gold text-[#021256] px-10 py-5 rounded-full font-black uppercase tracking-widest hover:bg-white transition-all duration-300 shadow-xl group">
                <svg class="w-6 h-6 transition-transform group-hover:translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download Guide
            </a>
        </div>

        {{-- 🛠️ QUICK STEPS SECTION --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-gray-600">
            <div>
                <div class="text-4xl font-black text-orbita-gold opacity-30 mb-4">01</div>
                <h3 class="text-xl font-bold text-orbita-blue uppercase mb-3">Preparation</h3>
                <p class="text-sm leading-relaxed">Ensure the door thickness is between 35mm and 55mm. Use the provided mortise template to mark the drilling points precisely on the door leaf.</p>
            </div>

            <div>
                <div class="text-4xl font-black text-orbita-gold opacity-30 mb-4">02</div>
                <h3 class="text-xl font-bold text-orbita-blue uppercase mb-3">Mortise Fit</h3>
                <p class="text-sm leading-relaxed">Install the lock mortise into the cutout. Secure the spindle and connect the data cables from the front panel to the back panel through the pre-drilled holes.</p>
            </div>

            <div>
                <div class="text-4xl font-black text-orbita-gold opacity-30 mb-4">03</div>
                <h3 class="text-xl font-bold text-orbita-blue uppercase mb-3">Programming</h3>
                <p class="text-sm leading-relaxed">Once batteries (4x AA Alkaline) are installed, use the Orbita Encoder to initialize the lock and sync it with your Hotel Management Software.</p>
            </div>
        </div>

        {{-- 🎥 VIDEO PREVIEW (Optional) --}}
        <div class="mt-20 rounded-[3rem] overflow-hidden bg-gray-100 aspect-video flex items-center justify-center relative group cursor-pointer border-4 border-white shadow-xl">
            <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=2070&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-700" alt="Video Placeholder">
            <div class="relative z-10 w-20 h-20 bg-orbita-blue text-white rounded-full flex items-center justify-center shadow-2xl group-hover:bg-orbita-gold transition-colors">
                <svg class="w-8 h-8 translate-x-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </div>
            <p class="absolute bottom-10 text-orbita-blue font-bold uppercase tracking-widest text-xs">Watch Installation Video</p>
        </div>

        

        <div class="mt-16 p-8 border border-gray-100 rounded-2xl bg-white text-center">
            <h4 class="text-orbita-blue font-bold mb-2">Need On-Site Assistance?</h4>
            <p class="text-gray-500 text-sm mb-6">Our technical team in Nairobi is available for bulk installations across East Africa.</p>
            <a href="{{ route('contact') }}" class="text-orbita-gold font-bold uppercase tracking-widest text-xs hover:underline">Contact Support →</a>
        </div>
    </div>
</x-app-layout>