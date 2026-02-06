<x-app-layout>
    <div class="max-w-2xl mx-auto px-6 py-32 text-center">
        <div class="w-20 h-20 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-8">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
            </svg>
        </div>
        
        <h1 class="text-3xl font-black text-orbita-blue uppercase mb-4">Unsubscribed Successfully</h1>
        <p class="text-gray-600 mb-8">
            The email <span class="font-bold text-orbita-blue">{{ $email }}</span> has been removed from our mailing list. You will no longer receive promotional updates from Orbita Kenya.
        </p>

        <a href="/" class="inline-block px-8 py-3 bg-orbita-blue text-white font-bold rounded-xl hover:bg-orbita-gold transition shadow-lg uppercase text-xs tracking-widest">
            Return to Homepage
        </a>
    </div>
</x-app-layout>