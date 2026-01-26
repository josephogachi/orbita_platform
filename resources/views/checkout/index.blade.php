@extends('layouts.public')

@section('content')
<div class="bg-orbita-light min-h-screen py-12">
    <div class="container mx-auto px-4">
        
        {{-- 1. Error Notification Block --}}
        @if ($errors->any())
            <div class="max-w-7xl mx-auto mb-8">
                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-2xl shadow-sm">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <h3 class="text-sm font-black text-red-800 uppercase tracking-widest">Action Required</h3>
                    </div>
                    <ul class="text-xs text-red-700 font-bold uppercase tracking-tight list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            {{-- 2. Hidden Email Field (Required by your Controller) --}}
            <input type="hidden" name="email" value="{{ auth()->user()->email }}">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                {{-- Left Column: Forms --}}
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-[2.5rem] p-10 shadow-xl border border-gray-100">
                        <h2 class="text-2xl font-black text-orbita-blue uppercase tracking-tighter mb-8">Shipping Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Full Name</label>
                                <input type="text" name="name" value="{{ auth()->user()->name }}" required class="w-full bg-orbita-light border-none rounded-xl p-4 text-sm focus:ring-2 focus:ring-orbita-gold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Phone Number (M-Pesa)</label>
                                <input type="text" name="phone" placeholder="e.g. 0706000999" required class="w-full bg-orbita-light border-none rounded-xl p-4 text-sm focus:ring-2 focus:ring-orbita-gold">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Delivery Address (Nairobi / County)</label>
                                <textarea name="address" rows="3" required class="w-full bg-orbita-light border-none rounded-xl p-4 text-sm focus:ring-2 focus:ring-orbita-gold" placeholder="Building, Floor, or Town..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] p-10 shadow-xl border border-gray-100">
                        <h2 class="text-2xl font-black text-orbita-blue uppercase tracking-tighter mb-4">Payment Method</h2>
                        <p class="text-gray-400 text-xs mb-6 font-medium">Securely pay via M-Pesa, Visa, or Mastercard via IntaSend.</p>
                        
                        <div class="p-4 bg-orbita-light rounded-2xl flex items-center gap-4 border border-orbita-blue/10">
                            <div class="w-10 h-10 bg-orbita-blue rounded-full flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <span class="font-black text-orbita-blue text-xs uppercase tracking-widest">Mobile Money & Card (Automated)</span>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-orbita-blue rounded-[2.5rem] p-10 text-white shadow-2xl sticky top-24">
                        <h3 class="text-xl font-black uppercase tracking-tighter mb-8 border-b border-white/10 pb-4">Order Summary</h3>
                        
                        <div class="space-y-4 mb-8">
                            @foreach($cartItems as $item)
                            <div class="flex justify-between text-xs font-bold">
                                <span class="text-white/70">{{ $item->quantity }}x {{ $item->name }}</span>
                                <span>KES {{ number_format($item->getPriceSum()) }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="space-y-2 border-t border-white/10 pt-6">
                            <div class="flex justify-between text-xs font-medium">
                                <span class="text-white/60">Subtotal</span>
                                <span>KES {{ number_format($total) }}</span>
                            </div>
                            <div class="flex justify-between text-xs font-medium">
                                <span class="text-white/60">Shipping</span>
                                <span class="text-orbita-gold uppercase">Calculated at delivery</span>
                            </div>
                            <div class="flex justify-between text-2xl font-black mt-4 pt-4 border-t border-white/10">
                                <span class="uppercase tracking-tighter">Total</span>
                                <span class="text-orbita-gold">KES {{ number_format($total) }}</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-10 bg-orbita-gold text-white py-5 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-white hover:text-orbita-blue transition shadow-xl group">
                            Pay Now
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection