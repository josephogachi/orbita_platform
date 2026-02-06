<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Restricted | Orbita Kenya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden relative">
        {{-- Decorative Top Bar --}}
        <div class="h-2 bg-gradient-to-r from-blue-900 to-[#d48d56]"></div>

        <div class="p-8 text-center">
            {{-- Icon --}}
            <div class="w-16 h-16 bg-blue-50 text-blue-900 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-slate-900 mb-2">Exclusive Catalog Access</h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
                You are trying to download our <span class="font-semibold text-blue-900">Technical Product Catalog</span>. 
                Please sign in or create a complimentary account to access detailed specifications, pricing, and installation guides.
            </p>

            <div class="space-y-3">
                {{-- Primary Action: Sign In --}}
                <a href="{{ route('login') }}" class="block w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 px-4 rounded-xl transition duration-200 shadow-lg shadow-blue-900/20">
                    Sign In to Continue
                </a>

                {{-- Secondary Action: Register --}}
                <a href="{{ route('register') }}" class="block w-full bg-white border-2 border-slate-100 hover:border-blue-100 text-slate-600 hover:text-blue-900 font-semibold py-3 px-4 rounded-xl transition duration-200">
                    Create New Account
                </a>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100">
                <a href="{{ route('home') }}" class="text-sm text-slate-400 hover:text-slate-600 font-medium flex items-center justify-center gap-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Return to Homepage
                </a>
            </div>
        </div>
    </div>

</body>
</html>