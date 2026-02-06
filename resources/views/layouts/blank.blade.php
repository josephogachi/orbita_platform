<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Orbita Kenya' }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Required for Livewire functionality --}}
    @livewireStyles
</head>
<body class="antialiased">
    {{-- This is where your self-contained Contact Page (with its own header/footer) will render --}}
    {{ $slot }}

    @livewireScripts
</body>
</html>