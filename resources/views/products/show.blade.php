@extends('layouts.public')

@section('content')
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        
        <nav class="flex mb-10 text-xs font-bold uppercase tracking-widest text-gray-400">
            <a href="/" class="hover:text-orbita-gold">Home</a>
            <span class="mx-3">/</span>
            <a href="#" class="hover:text-orbita-gold">{{ $product->category->name ?? 'Category' }}</a>
            <span class="mx-3 text-orbita-gold">/</span>
            <span class="text-orbita-blue">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            
            <div x-data="{ activeImg: 0 }">
                <div class="rounded-[3rem] bg-orbita-light p-8 overflow-hidden mb-6 border border-gray-100 shadow-inner">
                    <template x-if="{{ count($product->images ?? []) }} > 0">
                        <img :src="activeImg === 0 ? '{{ asset('storage/' . ($product->images[0] ?? '')) }}' : (activeImg === 1 ? '{{ asset('storage/' . ($product->images[1] ?? '')) }}' : '')" 
                             class="w-full h-[500px] object-contain mix-blend-multiply transition-all duration-500">
                    </template>
                </div>
                
                <div class="flex gap-4">
                    @foreach($product->images as $index => $img)
                        <button @click="activeImg = {{ $index }}" 
                                class="w-24 h-24 rounded-2xl border-2 p-2 transition"
                                :class="activeImg === {{ $index }} ? 'border-orbita-gold bg-white' : 'border-transparent bg-gray-50'">
                            <img src="{{ asset('storage/'.$img) }}" class="w-full h-full object-contain mix-blend-multiply">
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col">
                <div class="mb-8">
                    <span class="bg-orbita-gold/10 text-orbita-gold px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-4 inline-block">
                        SKU: {{ $product->sku }}
                    </span>
                    <h1 class="text-4xl md:text-5xl font-black text-orbita-blue uppercase leading-tight mb-4">
                        {{ $product->name }}
                    </h1>
                    <div class="flex items-center gap-6">
                        <span class="text-3xl font-black text-orbita-blue tracking-tighter">
                            KES {{ number_format($product->price) }}
                        </span>
                        @if($product->stock_quantity > 0)
                            <span class="text-green-500 text-xs font-bold flex items-center gap-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span> In Stock
                            </span>
                        @endif
                    </div>
                </div>

                <div class="prose prose-sm text-gray-500 mb-10 max-w-none">
                    <p class="text-lg leading-relaxed">{{ $product->description }}</p>
                </div>

                @if($product->technical_specs)
                <div class="bg-orbita-light rounded-[2rem] p-8 mb-10 border border-gray-100">
                    <h3 class="font-black text-orbita-blue uppercase text-xs tracking-widest mb-6">Technical Specifications</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 text-sm font-medium">
                        {{-- Assuming technical_specs is a textarea with lines --}}
                        @foreach(explode("\n", $product->technical_specs) as $spec)
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-orbita-gold mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-600">{{ $spec }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-4 mt-auto">
                    <button class="flex-1 bg-orbita-blue text-white py-5 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-orbita-gold transition shadow-xl">
                        Add to Cart
                    </button>
                    <a href="https://wa.me/254700000000?text=I'm interested in {{ $product->name }}" class="flex-1 border-2 border-gray-100 text-orbita-blue py-5 rounded-2xl font-black uppercase tracking-widest text-xs hover:border-orbita-blue transition text-center flex items-center justify-center gap-2">
                        WhatsApp Inquiry
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection