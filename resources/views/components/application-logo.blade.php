
@props(['mode' => 'light']) 

@php
    // The filenames here must match what you saved in public/images
    $logoName = ($mode === 'dark') ? 'orbita-logo-white.png' : 'orbita-logo.png';
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center justify-center']) }}>
    <img src="{{ asset('images/' . $logoName) }}" 
         alt="Orbita Kenya" 
         class="h-16 w-auto"
         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
    
    <span class="hidden text-2xl font-black {{ $mode === 'dark' ? 'text-white' : 'text-orbita-blue' }} tracking-tighter uppercase">
        ORBITA<span class="{{ $mode === 'dark' ? 'text-white/40' : 'text-gray-400' }}">KENYA</span>
    </span>
</div>
