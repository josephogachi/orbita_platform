@extends('layouts.public')

@section('content')
<div class="bg-orbita-light min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="mb-10">
            <h1 class="text-4xl font-black text-orbita-blue uppercase tracking-tighter">My Account</h1>
            <p class="text-gray-500 font-medium">Manage your orders and account settings.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-gray-100 flex flex-col items-center text-center">
                    <div class="w-24 h-24 bg-orbita-blue text-white rounded-full flex items-center justify-center text-4xl font-black mb-4 shadow-glow">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    
                    <h2 class="text-xl font-black text-orbita-blue uppercase tracking-tighter leading-tight">{{ $user->name }}</h2>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-8">{{ $user->email }}</p>
                    
                    <div class="w-full space-y-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('profile.edit') }}" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-orbita-light text-orbita-blue font-black text-[10px] uppercase tracking-widest hover:bg-orbita-gold hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Settings
                        </a>
                        
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-red-50 text-red-500 font-black text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orbita-blue to-blue-900 rounded-[2.5rem] p-8 text-white shadow-xl border border-white/10">
                    <div class="bg-white/20 w-12 h-12 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                    </div>
                    <h4 class="font-black uppercase tracking-tighter text-xl mb-1">Need Help?</h4>
                    <p class="text-xs text-blue-100 font-medium mb-6 leading-relaxed">Chat with our technical team regarding your locks, cards, or delivery.</p>
                    
                    <div class="space-y-3">
                        <button onclick="Tawk_API.toggle()" class="w-full py-4 bg-orbita-gold text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:scale-105 transition-all shadow-lg flex items-center justify-center gap-2">
                            Start Live Chat
                        </button>

                        <a href="https://wa.me/254726777733" target="_blank" class="w-full py-4 bg-[#25D366] text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:scale-105 transition-all shadow-lg flex items-center justify-center gap-2">
                            WhatsApp Support
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-gray-100 min-h-[600px]">
                    <div class="flex items-center justify-between mb-10">
                        <h3 class="text-2xl font-black text-orbita-blue uppercase tracking-tighter">Order History</h3>
                        <div class="bg-orbita-light px-4 py-2 rounded-full text-[10px] font-black text-orbita-blue uppercase tracking-widest">
                            Total Orders: {{ $orders->total() }}
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-4">
                            <thead>
                                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                    <th class="px-4 pb-2">Reference</th>
                                    <th class="px-4 pb-2">Date</th>
                                    <th class="px-4 pb-2">Status</th>
                                    <th class="px-4 pb-2 text-right">Amount</th>
                                    <th class="px-4 pb-2 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @forelse($orders as $order)
                                <tr class="group hover:bg-gray-50 transition-all">
                                    <td class="bg-white group-hover:bg-gray-50 p-4 rounded-l-2xl border-y border-l border-gray-50">
                                        <div class="font-black text-orbita-blue tracking-tight">{{ $order->order_number }}</div>
                                        <div class="text-[9px] text-gray-400 font-bold uppercase">{{ $order->payment_method ?? 'Online' }}</div>
                                    </td>
                                    <td class="bg-white group-hover:bg-gray-50 p-4 border-y border-gray-50 text-sm font-medium text-gray-600">
                                        {{ $order->created_at->format('d M, Y') }}
                                    </td>
                                    <td class="bg-white group-hover:bg-gray-50 p-4 border-y border-gray-50">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest 
                                            @if($order->status == 'delivered') bg-green-100 text-green-600 
                                            @elseif($order->status == 'cancelled') bg-red-100 text-red-600
                                            @elseif($order->status == 'processing') bg-blue-100 text-blue-600
                                            @else bg-gray-100 text-gray-500 @endif">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="bg-white group-hover:bg-gray-50 p-4 border-y border-gray-50 text-right">
                                        <div class="font-black text-orbita-blue leading-none">KES {{ number_format($order->grand_total, 2) }}</div>
                                        <div class="text-[9px] font-bold uppercase {{ $order->payment_status == 'paid' ? 'text-green-500' : 'text-red-400' }}">
                                            {{ $order->payment_status }}
                                        </div>
                                    </td>
                                    <td class="bg-white group-hover:bg-gray-50 p-4 rounded-r-2xl border-y border-r border-gray-50 text-right">
                                        <a href="{{ route('profile.invoice', $order->id) }}" class="inline-flex items-center justify-center p-3 rounded-xl bg-orbita-blue text-white hover:bg-orbita-gold transition-all shadow-md" title="Download Invoice">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-16 h-16 text-gray-100 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                            <p class="text-gray-400 font-black uppercase text-xs tracking-widest">No orders found yet</p>
                                            <a href="/" class="mt-4 text-orbita-gold font-bold text-xs hover:underline uppercase">Start Shopping</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection