<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Orbita Kenya') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 bg-[#F8FAFC] relative overflow-hidden">
            
            <div class="absolute top-0 left-0 w-full h-full z-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-orbita-blue/5 rounded-full blur-[120px] animate-pulse"></div>
                <div class="absolute top-[40%] -right-[10%] w-[40%] h-[60%] bg-orbita-blue/10 rounded-full blur-[100px]"></div>
                <div class="absolute -bottom-[10%] left-[20%] w-[30%] h-[30%] bg-orbita-blue/5 rounded-full blur-[80px]"></div>
            </div>

            <div class="relative z-10 w-full flex justify-center px-4">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>