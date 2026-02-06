<x-app-layout>
    <div class="bg-gray-50 min-h-screen pb-24">
        
        {{-- HERO HEADER --}}
        <div class="relative bg-orbita-blue py-20 md:py-28 overflow-hidden">
            {{-- Modern geometric overlay --}}
            <div class="absolute inset-0 opacity-10" 
                 style="background-image: radial-gradient(#CCA43B 1px, transparent 1px); background-size: 30px 30px;">
            </div>
            
            <div class="relative max-w-7xl mx-auto px-4 text-center z-10">
                <span class="inline-block py-1 px-3 rounded-full bg-white/10 text-orbita-gold text-[10px] font-black uppercase tracking-[0.2em] mb-6 border border-white/10 backdrop-blur-sm">
                    Portfolio & Case Studies
                </span>
                <h1 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter mb-6">
                    Our <span class="text-orbita-gold">Work</span>
                </h1>
                <p class="text-gray-300 max-w-xl mx-auto text-base md:text-lg font-medium leading-relaxed">
                    Explore how we define security and luxury standards for the leading hospitality brands in East Africa.
                </p>
            </div>
        </div>

        {{-- UNIFORM PROJECTS GRID --}}
        <div class="max-w-7xl mx-auto px-4 -mt-16 relative z-20">
            @if($projects->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-xl">
                    <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Projects are being uploaded</p>
                </div>
            @else
                {{-- Grid Layout: 1 col mobile, 2 col tablet, 3 col desktop --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($projects as $project)
                        <a href="{{ route('work.show', $project->slug) }}" class="group flex flex-col h-full bg-white rounded-[2rem] shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden border border-gray-100">
                            
                            {{-- 1. Image Container (Fixed Height for Uniformity) --}}
                            <div class="relative h-64 w-full overflow-hidden bg-gray-100">
                                @if($project->thumbnail_image)
                                    <img src="{{ asset('storage/' . $project->thumbnail_image) }}" 
                                         class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                                         alt="{{ $project->title }}">
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-300 bg-gray-100">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif

                                {{-- Overlay Gradient --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>

                                {{-- Category Badge (Top Left) --}}
                                <div class="absolute top-4 left-4">
                                    <span class="bg-white/95 backdrop-blur text-orbita-blue px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm">
                                        {{ $project->service_category ?? 'Project' }}
                                    </span>
                                </div>
                            </div>

                            {{-- 2. Content Container (Flex Grow pushes footer down) --}}
                            <div class="flex flex-col flex-grow p-8">
                                {{-- Header --}}
                                <div class="mb-4">
                                    <div class="flex items-center gap-2 text-orbita-gold text-[10px] font-black uppercase tracking-widest mb-2">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $project->location ?? 'Kenya' }}
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900 uppercase leading-tight group-hover:text-orbita-blue transition-colors line-clamp-2">
                                        {{ $project->title }}
                                    </h3>
                                </div>

                                {{-- Description (Strict Line Clamp for Uniformity) --}}
                                <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 mb-6">
                                    {{ Str::limit(strip_tags($project->description), 120) }}
                                </p>

                                {{-- Footer (Always at bottom) --}}
                                <div class="mt-auto pt-6 border-t border-gray-100 flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        {{ $project->completion_date ? $project->completion_date->format('M Y') : 'Completed' }}
                                    </span>
                                    
                                    <span class="flex items-center gap-2 text-orbita-blue text-[10px] font-black uppercase tracking-widest group-hover:gap-3 transition-all duration-300">
                                        View Case Study
                                        <svg class="w-3 h-3 text-orbita-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- CTA SECTION --}}
        <div class="max-w-4xl mx-auto mt-24 text-center px-4">
            <h2 class="text-2xl font-black text-orbita-blue uppercase mb-6">Have a project in mind?</h2>
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center bg-orbita-gold text-white px-10 py-4 rounded-full font-black uppercase tracking-widest text-xs hover:bg-orbita-blue hover:shadow-xl transition-all duration-300">
                Start Your Project
            </a>
        </div>

    </div>
</x-app-layout>