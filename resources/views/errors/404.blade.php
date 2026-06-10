@extends('layouts.public')

@section('title', 'Page Not Found - Orbita Kenya')

@section('content')
<section class="min-h-[70vh] flex items-center justify-center py-20 px-4 bg-gray-50 relative overflow-hidden">
    {{-- Decorative Background Glow --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] md:w-[600px] h-[400px] md:h-[600px] bg-orbita-blue/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="container mx-auto max-w-3xl text-center relative z-10">
        
        {{-- Theme-Appropriate Icon: A Locked/Broken Shield --}}
        <div class="mx-auto w-24 h-24 md:w-32 md:h-32 mb-8 text-gray-300 relative group">
            <svg class="w-full h-full drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {{-- Shield Outline --}}
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                {{-- Broken slash across it --}}
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="text-red-400" d="M4 4l16 16" />
            </svg>
        </div>

        {{-- 404 Heading --}}
        <h1 class="text-7xl md:text-[10rem] font-black text-[#021256] uppercase tracking-tighter mb-2 leading-none drop-shadow-md">
            404
        </h1>
        
        {{-- Clever Brand Copy --}}
        <h2 class="text-2xl md:text-4xl font-black text-gray-800 uppercase tracking-tight mb-6">
            This Door Is <span class="text-[#D4AF37]">Locked</span>.
        </h2>
        
        <p class="text-gray-500 text-sm md:text-lg max-w-xl mx-auto mb-12 leading-relaxed font-medium">
            We can't seem to find the page you're looking for. It might have been moved, deleted, or perhaps you just took a wrong turn in the hallway.
        </p>

        {{-- E-Commerce Recovery Buttons --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ url('/') }}" class="w-full sm:w-auto px-8 py-4 bg-[#021256] text-white font-black uppercase text-[10px] md:text-xs tracking-widest rounded-full hover:bg-[#D4AF37] hover:scale-105 transition-all duration-300 shadow-xl">
                Return to Lobby
            </a>
            
            {{-- Make sure this URL points to your actual shop page --}}
            <a href="{{ url('/products') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-[#021256] border-2 border-[#021256] font-black uppercase text-[10px] md:text-xs tracking-widest rounded-full hover:bg-[#021256] hover:text-white transition-colors duration-300 shadow-sm">
                Browse Catalog
            </a>
        </div>
    </div>
</section>
@endsection