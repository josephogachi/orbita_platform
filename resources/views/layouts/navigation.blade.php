<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            
            {{-- 1. LEFT SIDE: LOGO --}}
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-orbita-blue" />
                    </a>
                </div>

                {{-- Desktop Links --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">Home</x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">Products</x-nav-link>
                    <x-nav-link :href="route('work')" :active="request()->routeIs('work')">Our Work</x-nav-link>
                    <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')">Contact</x-nav-link>
                </div>
            </div>

            {{-- 2. RIGHT SIDE (DESKTOP) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                {{-- Desktop Cart Icon --}}
                <a href="{{ route('cart.index') }}" class="mr-6 relative group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 group-hover:text-orbita-blue transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    @if(!Cart::isEmpty())
                        <span class="absolute -top-2 -right-2 bg-orbita-gold text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shadow-sm">
                            {{ Cart::getContent()->count() }}
                        </span>
                    @endif
                </a>

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-bold rounded-xl text-orbita-blue bg-orbita-light hover:text-orbita-gold transition">
                                <div>{{ Auth::user()->name }}</div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">Portal</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">Settings</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-black text-orbita-blue mr-4">LOGIN</a>
                    <a href="{{ route('register') }}" class="bg-orbita-blue text-white px-4 py-2 rounded-lg text-xs font-bold">JOIN</a>
                @endauth
            </div>

            {{-- 3. RIGHT SIDE (MOBILE) --}}
            <div class="-me-2 flex items-center gap-4 sm:hidden">
                
                {{-- MOBILE CART ICON --}}
                <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-800 hover:text-orbita-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    @if(!Cart::isEmpty())
                        <span class="absolute top-0 right-0 bg-orbita-gold text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                            {{ Cart::getContent()->count() }}
                        </span>
                    @endif
                </a>

                {{-- HAMBURGER BUTTON --}}
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-800 hover:text-orbita-blue hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU DROPDOWN --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">Home</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">Products</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('work')" :active="request()->routeIs('work')">Our Work</x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Portal</x-responsive-nav-link>
            @endauth
        </div>
        
        {{-- Mobile Auth Section --}}
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 bg-gray-50">
                <div class="px-4">
                    <div class="font-bold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="p-4 grid grid-cols-2 gap-4">
                <a href="{{ route('login') }}" class="text-center py-2 border rounded-lg font-bold text-orbita-blue">Login</a>
                <a href="{{ route('register') }}" class="text-center py-2 bg-orbita-blue text-white rounded-lg font-bold">Sign Up</a>
            </div>
        @endauth
    </div>
</nav>