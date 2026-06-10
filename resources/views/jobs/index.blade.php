@extends('layouts.public')

@section('content')
<section class="py-20 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 max-w-5xl">
        
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-black text-orbita-blue uppercase tracking-tighter mb-4">Join Our Team</h1>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto">Build the future of smart hospitality technology with us. Explore our open roles below.</p>
        </div>

        @if($jobs->count() > 0)
            <div class="space-y-6">
                @foreach($jobs as $job)
                    <a href="{{ route('jobs.show', $job->slug) }}" class="block group">
                        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:border-orbita-gold/30 transition-all duration-300 flex flex-col md:flex-row md:items-center justify-between gap-6">
                            
                            <div>
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="px-3 py-1 bg-orbita-blue/5 text-orbita-blue rounded-full text-xs font-bold uppercase tracking-wider">
                                        {{ $job->department }}
                                    </span>
                                    <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-xs font-bold uppercase tracking-wider">
                                        {{ $job->employment_type }}
                                    </span>
                                </div>
                                <h2 class="text-2xl font-black text-orbita-blue group-hover:text-orbita-gold transition-colors">{{ $job->title }}</h2>
                                <div class="flex items-center gap-4 mt-3 text-sm text-gray-400 font-medium">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $job->location }}
                                    </span>
                                    @if($job->closing_date)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Closes {{ $job->closing_date->format('M d, Y') }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-orbita-blue group-hover:bg-orbita-blue group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </div>

                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm">
                <div class="w-20 h-20 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-orbita-blue mb-2">No Openings Available</h3>
                <p class="text-gray-500">We are currently fully staffed, but check back soon for new opportunities!</p>
            </div>
        @endif

    </div>
</section>
@endsection