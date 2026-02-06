<div> {{-- 👈 THIS IS THE REQUIRED WRAPPER --}}

    {{-- SECTION 1: The Main Content --}}
    <div class="bg-gray-50 min-h-screen">
        
        {{-- HEADER --}}
        <div class="bg-orbita-blue py-20 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#CCA43B 1px, transparent 1px); background-size: 30px 30px;"></div>
            <h1 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter relative z-10">
                Get in <span class="text-orbita-gold">Touch</span>
            </h1>
            <p class="text-gray-300 mt-4 max-w-xl mx-auto relative z-10">
                Visit our showroom or send us a message. Our technical team is ready to assist.
            </p>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-16 -mt-10 relative z-20">
            <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-gray-100 grid grid-cols-1 lg:grid-cols-2">
                
                {{-- CONTACT INFO COLUMN --}}
                <div class="bg-orbita-blue text-white p-12 md:p-16 flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-orbita-gold rounded-full opacity-10 blur-3xl"></div>
                    
                    <div class="space-y-12 relative z-10">
                        <div>
                            <h3 class="text-orbita-gold font-black uppercase tracking-widest text-xs mb-6">Contact Information</h3>
                            <h2 class="text-3xl font-bold leading-tight">Orbita Kenya<br>Headquarters</h2>
                        </div>

                        <div class="space-y-8">
                            {{-- Location --}}
                            <div class="flex items-start gap-4">
                                <div class="bg-white/10 p-3 rounded-xl text-orbita-gold">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-lg">Nairobi, Kenya</p>
                                    <p class="text-gray-400 text-sm">Westlands Commercial Center,<br>Ring Road Parklands</p>
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="flex items-start gap-4">
                                <div class="bg-white/10 p-3 rounded-xl text-orbita-gold">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-lg">Phone Support</p>
                                    <a href="tel:+254700000000" class="text-orbita-gold font-bold hover:underline">+254 700 000 000</a>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="flex items-start gap-4">
                                <div class="bg-white/10 p-3 rounded-xl text-orbita-gold">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-lg">Email Us</p>
                                    <a href="mailto:sales@orbitakenya.com" class="text-orbita-gold font-bold hover:underline">sales@orbitakenya.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FORM COLUMN --}}
                <div class="p-12 md:p-16 bg-white">
                    <form wire:submit.prevent="submit" class="space-y-6">
                        @if (session()->has('success'))
                            <div class="bg-green-50 text-green-800 p-4 rounded-xl flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="font-bold text-sm">{{ session('success') }}</span>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Your Name</label>
                                <input type="text" wire:model="name" class="w-full bg-gray-50 border-none rounded-xl p-4 text-sm focus:ring-2 focus:ring-orbita-gold">
                                @error('name') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Phone Number</label>
                                <input type="text" wire:model="phone" class="w-full bg-gray-50 border-none rounded-xl p-4 text-sm focus:ring-2 focus:ring-orbita-gold">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Email Address</label>
                            <input type="email" wire:model="email" class="w-full bg-gray-50 border-none rounded-xl p-4 text-sm focus:ring-2 focus:ring-orbita-gold">
                            @error('email') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Subject</label>
                            <select wire:model="subject" class="w-full bg-gray-50 border-none rounded-xl p-4 text-sm focus:ring-2 focus:ring-orbita-gold">
                                <option value="">Select a topic...</option>
                                <option value="Sales Inquiry">Sales Inquiry</option>
                                <option value="Technical Support">Technical Support</option>
                                <option value="Partnership">Partnership Proposal</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('subject') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Message</label>
                            <textarea wire:model="message" rows="4" class="w-full bg-gray-50 border-none rounded-xl p-4 text-sm focus:ring-2 focus:ring-orbita-gold"></textarea>
                            @error('message') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full bg-orbita-gold text-white py-5 rounded-xl font-black uppercase tracking-widest text-xs hover:bg-orbita-blue transition-all shadow-lg flex justify-center">
                            <span wire:loading.remove>Send Message</span>
                            <span wire:loading>Sending...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 2: The Google Map --}}
    <div class="w-full h-96 bg-gray-200">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.8475510688636!2d36.7981!3d-1.2642!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMsKwMTUnNTEuMSJTIDM2wrA0Nyc1My4yIkU!5e0!3m2!1sen!2ske!4v1234567890" 
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" class="filter grayscale hover:grayscale-0 transition-all duration-700"></iframe>
    </div>

</div> {{-- 👈 THIS CLOSING DIV IS CRITICAL --}}