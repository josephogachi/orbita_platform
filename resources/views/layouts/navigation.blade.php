<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-orbita-blue" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        {{ __('Products') }}
                    </x-nav-link>

                    <x-nav-link :href="route('work')" :active="request()->routeIs('work')">
                        {{ __('Our Work') }}
                    </x-nav-link>

                    <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
                        {{ __('About') }}
                    </x-nav-link>

                    <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                        {{ __('Contact') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <div class="flex items-center gap-4">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Portal') }}
                        </x-nav-link>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-bold rounded-xl text-orbita-blue bg-orbita-light hover:text-orbita-gold transition duration-150 ease-in-out">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.index')">
                                    {{ __('My Orders & Invoices') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Account Settings') }}
                                </x-dropdown-link>
                                <hr class="border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="text-sm font-black uppercase tracking-widest text-orbita-blue hover:text-orbita-gold">Login</a>
                        <a href="{{ route('register') }}" class="bg-orbita-blue text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg hover:bg-orbita-gold transition">Join Orbita</a>
                    </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-orbita-blue hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">Home</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">Products</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('work')" :active="request()->routeIs('work')">Our Work</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')">Contact</x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Portal</x-responsive-nav-link>
            @endauth
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 bg-orbita-light">
                <div class="px-4">
                    <div class="font-black text-orbita-blue">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.index')">My Orders</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="p-4 border-t border-gray-100 space-y-2">
                <a href="{{ route('login') }}" class="block w-full text-center py-3 font-black uppercase tracking-widest text-orbita-blue">Login</a>
                <a href="{{ route('register') }}" class="block w-full text-center py-3 bg-orbita-blue text-white rounded-xl font-black uppercase tracking-widest">Register</a>
            </div>
        @endauth
    </div>
</nav>