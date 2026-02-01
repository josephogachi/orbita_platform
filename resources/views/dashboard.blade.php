<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-2xl font-black text-orbita-blue uppercase tracking-tight mb-8">My Account</h1>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    {{-- Profile Summary Card --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">User Profile</p>
                        <h3 class="font-bold text-gray-900">{{ auth()->user()->name }}</h3>
                        <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                    </div>

                    {{-- Order Summary Card --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Orders</p>
                        <h3 class="font-bold text-gray-900">{{ $orders->count() }}</h3>
                        <p class="text-sm text-gray-500">To date</p>
                    </div>
                </div>

                <h2 class="text-lg font-bold text-gray-900 mb-4">Order History</h2>
                
                @if($orders->isEmpty())
                    <div class="bg-white p-12 rounded-2xl text-center border border-dashed border-gray-200">
                        <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
                        <a href="{{ route('products.index') }}" class="inline-block bg-orbita-blue text-white px-6 py-2 rounded-full text-xs font-bold uppercase tracking-widest">Start Shopping</a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($orders as $order)
                            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                                <div>
                                    <p class="text-xs font-black text-orbita-gold uppercase">#{{ $order->order_number }}</p>
                                    <p class="text-sm font-bold text-gray-900">KES {{ number_format($order->grand_total, 2) }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $order->created_at->format('M d, Y') }}</p>
                                </div>

                                <div class="flex items-center gap-4">
                                    {{-- Payment Badge --}}
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter 
                                        {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $order->payment_status }}
                                    </span>

                                    {{-- Fulfillment Badge --}}
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter bg-gray-100 text-gray-600">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>