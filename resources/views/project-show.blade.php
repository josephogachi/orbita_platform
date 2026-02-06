<x-app-layout>
    <div class="bg-white min-h-screen pb-20">
        
        {{-- HERO SECTION --}}
        <div class="relative h-[60vh] w-full overflow-hidden">
            <div class="absolute inset-0 bg-gray-900/40 z-10"></div>
            {{-- Background Image --}}
            <img src="{{ asset('storage/' . $project->thumbnail_image) }}" class="w-full h-full object-cover" alt="{{ $project->title }}">
            
            <div class="absolute inset-0 z-20 flex flex-col justify-center items-center text-center px-4">
                <span class="bg-orbita-gold text-white px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-4 shadow-xl">
                    {{ $project->service_category ?? 'Project Showcase' }}
                </span>
                <h1 class="text-4xl md:text-7xl font-black text-white uppercase tracking-tighter drop-shadow-lg max-w-5xl">
                    {{ $project->title }}
                </h1>
                <div class="flex items-center gap-2 text-gray-200 mt-4 text-sm font-bold uppercase tracking-widest">
                    <svg class="w-5 h-5 text-orbita-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $project->location ?? 'Kenya' }}
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 -mt-20 relative z-30">
            <div class="bg-white rounded-[3rem] shadow-2xl p-8 md:p-16 border border-gray-100">
                
                {{-- CONTENT GRID --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    
                    {{-- Left: Project Info --}}
                    <div class="col-span-1 space-y-8 border-b lg:border-b-0 lg:border-r border-gray-100 pb-8 lg:pb-0 lg:pr-8">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Client</p>
                            <p class="text-xl font-bold text-orbita-blue">{{ $project->client_name ?? 'Confidential' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Completion Date</p>
                            <p class="text-xl font-bold text-orbita-blue">{{ $project->completion_date ? $project->completion_date->format('F Y') : 'Ongoing' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Scope</p>
                            <p class="text-xl font-bold text-orbita-blue">{{ $project->service_category }}</p>
                        </div>
                        
                        <a href="{{ route('contact') }}" class="block w-full text-center bg-orbita-blue text-white py-4 rounded-xl font-black uppercase tracking-widest text-xs hover:bg-orbita-gold transition">
                            Request Similar Setup
                        </a>
                    </div>

                    {{-- Right: Description --}}
                    <div class="col-span-1 lg:col-span-2 prose prose-lg prose-blue max-w-none text-gray-600">
                        {!! Str::markdown($project->description ?? '') !!}
                    </div>
                </div>

                {{-- GALLERY GRID --}}
                @if($project->gallery_images && count($project->gallery_images) > 0)
                    <div class="mt-20">
                        <h3 class="text-2xl font-black text-orbita-blue uppercase tracking-tighter mb-8 border-l-4 border-orbita-gold pl-4">Project Gallery</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($project->gallery_images as $image)
                                <div class="relative group h-64 rounded-2xl overflow-hidden cursor-pointer">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all z-10"></div>
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                                         alt="Gallery Image">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
            
            <div class="mt-12 text-center">
                <a href="{{ route('work') }}" class="text-gray-400 font-bold uppercase tracking-widest text-xs hover:text-orbita-blue transition">
                    &larr; Back to All Projects
                </a>
            </div>
        </div>

    </div>
</x-app-layout>