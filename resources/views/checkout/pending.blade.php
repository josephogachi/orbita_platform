@extends('layouts.public')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center bg-orbita-light py-20">
    <div class="max-w-xl w-full mx-4 text-center">
        <div class="animate-spin inline-block w-12 h-12 border-[6px] border-current border-t-transparent text-orbita-gold rounded-full mb-8" role="status"></div>
        <h1 class="text-4xl font-black text-orbita-blue uppercase tracking-tighter mb-4">Verifying Payment...</h1>
        <p class="text-gray-500 font-medium mb-8">We are waiting for M-Pesa confirmation. Please do not refresh this page.</p>
        
        <script>
            // Simple script to refresh every 5 seconds until status changes
            setTimeout(function(){
               window.location.reload();
            }, 5000);
        </script>
    </div>
</div>
@endsection