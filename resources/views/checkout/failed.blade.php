@extends('layouts.public')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center bg-orbita-light py-20">
    <div class="max-w-xl w-full mx-4">
        <div class="bg-white rounded-[3rem] p-12 shadow-2xl text-center border border-gray-100">
            <div class="w-24 h-24 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>

            <h1 class="text-4xl font-black text-orbita-blue uppercase tracking-tighter mb-4">Payment Failed</h1>
            <p class="text-gray-500 mb-10 font-medium">We couldn't process your payment. This might be due to a cancelled M-Pesa prompt or insufficient balance.</p>

            <div class="flex flex-col gap-4">
                <a href="/checkout" class="bg-orbita-blue text-white py-5 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-orbita-gold transition-all shadow-xl">
                    Try Again
                </a>
                <a href="https://wa.me/254700000000" class="text-orbita-gold font-black uppercase tracking-widest text-xs hover:underline">
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</div>
@endsection