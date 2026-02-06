@extends('layouts.public')

@section('content')
<div class="container mx-auto px-4 py-20 text-center">
    <div class="max-w-md mx-auto bg-white p-10 rounded-[3rem] shadow-xl border border-gray-100">
        <div class="relative flex justify-center mb-8">
            <div class="absolute animate-ping inline-flex h-20 w-20 rounded-full bg-green-400 opacity-20"></div>
            <div class="relative rounded-full bg-green-100 p-6">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-2xl font-black text-orbita-blue uppercase mb-4">Awaiting Payment</h1>
        <p class="text-gray-500 mb-8 font-medium">
            Please check your phone. A prompt has been sent to your M-Pesa. Enter your PIN to complete <strong>KES {{ number_format($order->grand_total) }}</strong>.
        </p>

        <div class="w-full bg-gray-100 rounded-full h-2 mb-8 overflow-hidden">
            <div class="bg-green-500 h-full animate-[progress_10s_ease-in-out_infinite]" style="width: 30%"></div>
        </div>

        <div class="text-xs text-gray-400 font-bold uppercase tracking-widest">
            Order Reference: {{ $order->order_number }}
        </div>
    </div>
</div>

<style>
    @keyframes progress {
        0% { width: 0%; }
        100% { width: 100%; }
    }
</style>

{{-- Optional: Auto-refresh or AJAX check --}}
<script>
    // Every 5 seconds, check if the order status has changed to 'paid'
    setInterval(function() {
        fetch('/api/check-order-status/{{ $order->id }}')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'paid' || data.status === 'processing') {
                    window.location.href = "{{ route('dashboard') }}?payment=success";
                }
            });
    }, 5000);
</script>
@endsection