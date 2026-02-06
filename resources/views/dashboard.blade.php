<x-app-layout>
    {{-- 1. HEADER STATS SECTION --}}
    <div class="bg-orbita-blue text-white pb-20 pt-10">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold font-heading">Welcome back, {{ Auth::user()->name }}</h1>
                    <p class="text-white/60 text-sm">Manage your hotel projects and orders.</p>
                </div>
                
                {{-- Quick Catalog Download Button --}}
                <a href="{{ route('catalog.download') }}" class="group flex items-center gap-3 bg-orbita-gold text-orbita-blue px-5 py-3 rounded-xl font-bold transition hover:bg-white hover:shadow-lg">
                    <div class="bg-white/20 p-1.5 rounded-lg group-hover:bg-orbita-blue/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </div>
                    <span>Download 2026 Catalog</span>
                </a>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl border border-white/5">
                    <div class="text-white/50 text-xs uppercase tracking-wider font-bold mb-1">Total Orders</div>
                    <div class="text-2xl font-bold">{{ Auth::user()->orders()->count() }}</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl border border-white/5">
                    <div class="text-white/50 text-xs uppercase tracking-wider font-bold mb-1">Active Projects</div>
                    <div class="text-2xl font-bold text-orbita-gold">{{ Auth::user()->orders()->where('status', '!=', 'completed')->count() }}</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl border border-white/5">
                    <div class="text-white/50 text-xs uppercase tracking-wider font-bold mb-1">Total Spent</div>
                    <div class="text-2xl font-bold">KES {{ number_format(Auth::user()->orders()->sum('grand_total') / 1000, 1) }}k</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl border border-white/5 flex items-center justify-between cursor-pointer hover:bg-white/20 transition" onclick="Tawk_API.toggle()">
                    <div>
                        <div class="text-white/50 text-xs uppercase tracking-wider font-bold mb-1">Support Status</div>
                        <div class="text-sm font-bold text-green-400 flex items-center gap-1">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span> Online Now
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CONTENT GRID --}}
    <div class="container mx-auto px-4 -mt-10 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- LEFT COLUMN: ORDER HISTORY --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="font-bold text-gray-800 text-lg">Recent Orders</h3>
                        <a href="{{ route('products.index') }}" class="text-sm text-orbita-blue hover:text-orbita-gold font-medium">New Order &rarr;</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="px-6 py-4 text-left">Order ID</th>
                                    <th class="px-6 py-4 text-left">Date</th>
                                    <th class="px-6 py-4 text-left">Status</th>
                                    <th class="px-6 py-4 text-right">Total</th>
                                    <th class="px-6 py-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse(Auth::user()->orders()->latest()->take(5)->get() as $order)
                                <tr class="hover:bg-gray-50/80 transition group">
                                    <td class="px-6 py-4 font-mono text-sm text-gray-600">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold
                                            @if($order->status == 'completed') bg-green-100 text-green-700
                                            @elseif($order->status == 'processing') bg-blue-100 text-blue-700
                                            @elseif($order->status == 'cancelled') bg-red-100 text-red-700
                                            @else bg-yellow-100 text-yellow-700 @endif">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-800">KES {{ number_format($order->grand_total) }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('profile.invoice', $order) }}" class="text-gray-400 hover:text-orbita-blue transition p-2" title="Download Invoice">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                            <p class="text-sm">No orders found.</p>
                                            <a href="{{ route('products.index') }}" class="mt-4 text-orbita-blue font-bold text-sm hover:underline">Browse Products</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: SIDEBAR --}}
            <div class="space-y-6">
                
                {{-- 1. Tracking / Notifications --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orbita-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        Tracking Updates
                    </h4>
                    
                    @php
                        $latestOrder = Auth::user()->orders()->latest()->first();
                    @endphp

                    @if($latestOrder)
                    <div class="relative pl-4 border-l-2 border-orbita-blue/20 space-y-6">
                        <div class="relative">
                            <span class="absolute -left-[21px] top-1 w-3 h-3 bg-orbita-blue rounded-full border-2 border-white ring-2 ring-blue-100"></span>
                            <p class="text-sm font-bold text-gray-800">Latest Order #{{ str_pad($latestOrder->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Status: {{ ucfirst($latestOrder->status) }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $latestOrder->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @else
                    <p class="text-sm text-gray-400 italic">No recent activity.</p>
                    @endif
                </div>

                {{-- 2. Need Help Box (Updated) --}}
                <div class="bg-gradient-to-br from-orbita-blue to-blue-900 rounded-2xl shadow-lg p-6 text-white text-center relative overflow-hidden">
                    {{-- Decorative Background Blur --}}
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
                    
                    <h4 class="font-bold text-lg mb-2 relative z-10">Need Assistance?</h4>
                    <p class="text-white/70 text-sm mb-6 relative z-10">Connect directly with our sales team for instant support.</p>
                    
                    <div class="space-y-3 relative z-10">
                        {{-- 1. Live Chat Button (Triggers Tawk.to) --}}
                        <button onclick="Tawk_API.toggle()" class="w-full bg-white text-orbita-blue font-bold py-3 px-4 rounded-xl hover:bg-orbita-gold hover:text-white transition shadow-lg flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 text-orbita-blue group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            Live Sales Chat
                        </button>

                        {{-- 2. WhatsApp Button --}}
                        <a href="https://wa.me/254726777733?text=Hello%20Orbita%20Team%2C%20I%20am%20contacting%20you%20from%20my%20Client%20Dashboard." target="_blank" class="w-full bg-[#25D366] text-white font-bold py-3 px-4 rounded-xl hover:bg-[#128C7E] transition shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            WhatsApp Us
                        </a>
                    </div>
                    
                    <div class="mt-4 flex justify-center items-center gap-2 text-xs text-white/50 relative z-10">
                        <span>or call</span>
                        <a href="tel:+254 726 777 733" class="hover:text-white font-bold transition">+254 726 777 733</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>