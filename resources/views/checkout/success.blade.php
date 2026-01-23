@extends('layouts.public')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center bg-orbita-light py-20">
    <div class="max-w-xl w-full mx-4">
        <div class="bg-white rounded-[3rem] p-12 shadow-2xl text-center border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            
            <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-4xl font-black text-orbita-blue uppercase tracking-tighter mb-4">Payment Successful!</h1>
            <p class="text-gray-500 mb-8 font-medium">Thank you for choosing Orbita. Your order is being processed and will be delivered shortly.</p>

            <div class="bg-orbita-light rounded-2xl p-6 mb-10 border border-gray-100 text-left">
                <div class="flex justify-between mb-2 text-sm">
                    <span class="text-gray-400 font-bold uppercase tracking-widest">Order Number:</span>
                    <span class="text-orbita-blue font-black">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400 font-bold uppercase tracking-widest">Tracking ID:</span>
                    <span class="text-orbita-gold font-black">{{ $tracking_id ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <a href="/" class="bg-orbita-blue text-white py-5 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-orbita-gold transition-all shadow-xl">
                    Continue Shopping
                </a>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">A receipt has been sent to your email.</p>
            </div>
        </div>
    </div>
</div>
@endsection