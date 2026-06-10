@extends('layouts.public')

@section('content')
<section class="py-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 max-w-6xl">
        
        <!-- Back Button -->
        <a href="{{ route('jobs.index') }}" class="inline-flex items-center text-gray-500 hover:text-orbita-blue transition-colors mb-8 font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to all jobs
        </a>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl flex items-center gap-4 shadow-sm">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h4 class="font-bold text-lg">Application Submitted!</h4>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <!-- Left Column: Job Details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Header -->
                <div class="bg-white rounded-3xl p-8 md:p-10 border border-gray-100 shadow-sm">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="px-4 py-1.5 bg-orbita-blue/5 text-orbita-blue rounded-full text-sm font-bold uppercase tracking-wider">
                            {{ $job->department }}
                        </span>
                        <span class="px-4 py-1.5 bg-green-50 text-green-600 rounded-full text-sm font-bold uppercase tracking-wider">
                            {{ $job->employment_type }}
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-orbita-blue mb-4">{{ $job->title }}</h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-gray-500 font-medium">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $job->location }}
                        </span>
                        @if($job->closing_date)
                        <span class="flex items-center gap-2 text-red-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Apply by {{ $job->closing_date->format('M d, Y') }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Description content (Rich Text) -->
                <div class="bg-white rounded-3xl p-8 md:p-10 border border-gray-100 shadow-sm prose prose-lg prose-blue max-w-none">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Role Overview</h3>
                    <div class="text-gray-600">
                        {!! $job->description !!}
                    </div>

                    @if($job->requirements)
                        <hr class="my-8 border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Requirements</h3>
                        <div class="text-gray-600">
                            {!! $job->requirements !!}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Application Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-lg sticky top-8">
                    <h3 class="text-2xl font-black text-orbita-blue mb-6">Apply Now</h3>
                    
                    <form action="{{ route('jobs.apply', $job->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">First Name *</label>
                                <input type="text" name="first_name" required value="{{ old('first_name') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orbita-blue focus:ring-2 focus:ring-orbita-blue/20 transition-all">
                                @error('first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Last Name *</label>
                                <input type="text" name="last_name" required value="{{ old('last_name') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orbita-blue focus:ring-2 focus:ring-orbita-blue/20 transition-all">
                                @error('last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Email Address *</label>
                            <input type="email" name="email" required value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orbita-blue focus:ring-2 focus:ring-orbita-blue/20 transition-all">
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Phone Number *</label>
                            <input type="tel" name="phone" required value="{{ old('phone') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orbita-blue focus:ring-2 focus:ring-orbita-blue/20 transition-all">
                            @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Resume / CV *</label>
                            <input type="file" name="resume" accept=".pdf,.doc,.docx" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:border-orbita-blue focus:ring-2 focus:ring-orbita-blue/20 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orbita-blue file:text-white hover:file:bg-blue-700">
                            <p class="text-xs text-gray-400 mt-2">Accepted formats: PDF, DOC, DOCX (Max 5MB)</p>
                            @error('resume') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Portfolio / LinkedIn URL</label>
                            <input type="url" name="portfolio_url" value="{{ old('portfolio_url') }}" placeholder="https://" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orbita-blue focus:ring-2 focus:ring-orbita-blue/20 transition-all">
                            @error('portfolio_url') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Cover Letter (Optional)</label>
                            <textarea name="cover_letter" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orbita-blue focus:ring-2 focus:ring-orbita-blue/20 transition-all">{{ old('cover_letter') }}</textarea>
                            @error('cover_letter') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full bg-orbita-blue text-white font-bold text-lg py-4 rounded-xl hover:bg-blue-800 transition-colors shadow-md">
                            Submit Application
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection