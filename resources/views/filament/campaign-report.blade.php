@php
    $total = count($total_list);
    $openedCount = collect($log)->filter(fn($d) => (is_array($d) && ($d['status'] ?? '') === 'opened') || (!is_array($d)))->count();
    $failedCount = collect($log)->filter(fn($d) => is_array($d) && ($d['status'] ?? '') === 'failed')->count();
    $deliveredCount = $total > 0 ? ($total - $failedCount) : 0;
    $openRate = $total > 0 ? round(($openedCount / $total) * 100) : 0;
    $failRate = $total > 0 ? round(($failedCount / $total) * 100) : 0;
@endphp

<div class="space-y-6 font-sans">
    
    <div class="pb-2 border-b border-gray-100 dark:border-gray-800">
        <h2 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">Campaign Performance Overview</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Real-time metrics and delivery audit for this promotion.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        
        <div class="relative bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-2.5 py-1 rounded-md">100% Base</span>
            </div>
            <div>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $total }}</h3>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Total Target Audience</p>
            </div>
        </div>

        <div class="relative bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-500"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path></svg>
                </div>
                <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/40 px-2.5 py-1 rounded-md">{{ $openRate }}% Rate</span>
            </div>
            <div>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $openedCount }}</h3>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Successfully Opened</p>
            </div>
        </div>

        <div class="relative bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-rose-500 to-red-600"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-full bg-rose-50 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="text-xs font-bold text-rose-700 dark:text-rose-400 bg-rose-100 dark:bg-rose-900/40 px-2.5 py-1 rounded-md">{{ $failRate }}% Bounced</span>
            </div>
            <div>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $failedCount }}</h3>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Delivery Failures</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden mt-4">
        <div class="max-h-[400px] overflow-y-auto">
            <table class="w-full text-left text-sm border-collapse whitespace-nowrap">
                <thead class="bg-gray-50/80 dark:bg-gray-800/80 sticky top-0 z-10 backdrop-blur-sm">
                    <tr>
                        <th class="py-3 px-5 font-bold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">Recipient Email</th>
                        <th class="py-3 px-5 font-bold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">Status</th>
                        <th class="py-3 px-5 font-bold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-200 dark:border-gray-700 text-right">Activity Log</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60">
                    @forelse($log as $email => $data)
                        @php
                            $status = is_array($data) ? ($data['status'] ?? 'opened') : 'opened';
                            $timestamp = is_array($data) ? ($data['at'] ?? $data['error'] ?? 'No log') : $data;
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="py-3 px-5 font-medium text-gray-900 dark:text-gray-200">
                                {{ $email }}
                            </td>
                            <td class="py-3 px-5">
                                @if($status === 'opened')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-500/20 text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Opened
                                    </span>
                                @elseif($status === 'failed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 border border-rose-200/60 dark:border-rose-500/20 text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Failed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Sent
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-gray-500 dark:text-gray-400 text-xs text-right truncate max-w-[200px]" title="{{ $timestamp }}">
                                {{ $timestamp }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No analytics data available.</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Metrics will appear here once the campaign is dispatched.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>