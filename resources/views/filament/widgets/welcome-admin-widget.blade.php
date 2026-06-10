<x-filament-widgets::widget>
    <x-filament::section class="relative overflow-hidden border-t-4 border-t-primary-600">
        @php
            $user = auth()->user();
            $hour = now()->timezone('Africa/Nairobi')->format('H');
            
            if ($hour < 12) {
                $greeting = 'Good morning';
            } elseif ($hour < 17) {
                $greeting = 'Good afternoon';
            } else {
                $greeting = 'Good evening';
            }

            $date = now()->timezone('Africa/Nairobi')->format('l, F jS, Y');
            $role = ucwords(str_replace('_', ' ', $user->role ?? 'Administrator'));
        @endphp

        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between py-2">
            
            {{-- User Info --}}
            <div class="flex items-center gap-4">
                <div class="rounded-full bg-gray-100 p-1 dark:bg-gray-800 shadow-sm ring-1 ring-gray-200 dark:ring-white/10">
                    <x-filament-panels::avatar.user :user="$user" size="lg" />
                </div>
                
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                        {{ $greeting }}, {{ explode(' ', trim($user->name))[0] }}.
                    </h2>
                    <p class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ $date }} • <span class="text-primary-600 dark:text-primary-400 font-semibold uppercase tracking-wider text-[10px]">{{ $role }}</span>
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 shrink-0">
                <x-filament::button
                    color="gray"
                    icon="heroicon-m-building-storefront"
                    tag="a"
                    href="{{ url('/') }}"
                    target="_blank"
                    size="sm"
                    class="font-bold uppercase tracking-widest text-[10px]"
                >
                    View Store
                </x-filament::button>

                <x-filament::button
                    color="primary"
                    icon="heroicon-m-arrow-path"
                    tag="button"
                    onclick="window.location.reload()"
                    size="sm"
                    class="font-bold uppercase tracking-widest text-[10px]"
                >
                    Sync
                </x-filament::button>
            </div>
            
        </div>
    </x-filament::section>
</x-filament-widgets::widget>