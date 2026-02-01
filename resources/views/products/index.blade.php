@extends('layouts.public')

@section('content')
    <div class="bg-gray-50 min-h-screen pb-20">
        {{-- Your Header Code --}}
        
        <div class="container mx-auto px-4">
            @livewire('product-filters')
        </div>
    </div>
@endsection