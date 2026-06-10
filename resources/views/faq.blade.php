@extends('layouts.public')

{{-- 🎯 DYNAMIC GOOGLE FAQ SCHEMA --}}
@push('schema')
@php
    // Automatically build the Google SEO schema from your database!
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqs->map(function($faq) {
            return [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    // strip_tags ensures HTML from Filament doesn't break the JSON
                    'text' => strip_tags($faq->answer) 
                ]
            ];
        })->toArray()
    ];
@endphp
<script type="application/ld+json">
    {!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<main class="bg-gray-50 min-h-screen pb-24 pt-12 md:pt-20">
    <div class="container mx-auto px-4 max-w-4xl">
        
        {{-- Header Section --}}
        <div class="text-center mb-16">
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-orbita-gold bg-orbita-gold/10 px-3 py-1 rounded-full mb-4 inline-block">Support Center</span>
            <h1 class="text-4xl md:text-6xl font-black text-orbita-blue uppercase tracking-tighter mb-6">Frequently Asked <br> Questions.</h1>
            <p class="text-gray-500 text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
                Everything you need to know about procuring, installing, and maintaining premium hospitality technology in East Africa.
            </p>
        </div>

        {{-- Dynamic FAQ Accordion Loop --}}
        <div class="space-y-4">
            @forelse($faqs as $faq)
                <div x-data="{ open: false }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button @click="open = !open" class="w-full px-6 py-6 text-left flex justify-between items-center focus:outline-none">
                        <h3 class="font-bold text-orbita-blue pr-8" :class="{'text-orbita-gold': open}">
                            {{ $faq->question }}
                        </h3>
                        <div class="shrink-0 text-gray-400 transition-transform duration-300" :class="{'rotate-180 text-orbita-gold': open}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </button>
                    <div x-show="open" x-collapse x-cloak>
                        <div class="px-6 pb-6 text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-4 prose prose-sm max-w-none prose-a:text-orbita-gold prose-strong:text-orbita-blue">
                            {{-- We use {!! !!} because the answer might contain bold text or links from Filament's Rich Editor --}}
                            {!! $faq->answer !!}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Questions are currently being updated.</p>
                </div>
            @endforelse
        </div>
        
        {{-- Call to Action --}}
        <div class="mt-16 text-center bg-orbita-blue rounded-[3rem] p-10 relative overflow-hidden shadow-2xl shadow-orbita-blue/20">
            <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-white uppercase tracking-tight mb-4">Still have questions?</h3>
                <p class="text-white/80 text-sm mb-8">Our security experts are ready to design a custom solution for your property.</p>
                <a href="{{ route('contact') }}" class="inline-block bg-orbita-gold text-white font-black uppercase tracking-widest text-[10px] px-8 py-4 rounded-xl hover:bg-white hover:text-orbita-blue transition-colors shadow-lg">
                    Contact Our Team
                </a>
            </div>
        </div>

    </div>
</main>
@endsection