<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Orbita Checkout' }}</title>
        
        <script src="https://cdn.tailwindcss.com"></script>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 font-sans antialiased">
        
        <header class="bg-white shadow-sm mb-6">
            <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
                <a href="/" class="text-xl font-bold text-blue-600">Orbita</a>
                <a href="/cart" class="text-gray-600 hover:text-blue-600">Back to Cart</a>
            </div>
        </header>

        <main>
            {{ $slot }}
        </main>

    </body>
</html>