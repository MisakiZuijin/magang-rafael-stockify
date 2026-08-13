{{-- resources/views/components/table/user-activity-card.blade.php --}}
@props(['user'])

@php
$typeBadge = fn($type) => $type == 'Masuk'
? ['bg-green-400 text-green-900', 'Masuk']
: ['bg-red-400 text-red-900', 'Keluar'];

$statusBadge = fn($status) => match($status) {
'Diterima' => 'bg-green-400 text-green-900',
'Pending' => 'bg-yellow-400 text-yellow-900',
'Dikeluarkan' => 'bg-blue-400 text-blue-900',
default => 'bg-red-400 text-red-900',
};
@endphp

<div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 grid place-items-center text-xs font-bold">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->role }} • {{ $user->stock_transactions_count }} transaksi</p>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto max-h-[300px] scrollbar-hide">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs uppercase sticky top-0 text-center">
                <tr>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Produk</th>
                    <th class="px-4 py-2 text-center">Tipe</th>
                    <th class="px-4 py-2 text-center">Qty</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Note</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-center">
                @foreach($user->stockTransactions as $activity)
                @php [$actClass, $actLabel] = $typeBadge($activity->type); @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <td class="px-4 py-2 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                        {{ $activity->date ? \Carbon\Carbon::parse($activity->date)->format('d M Y') : '-' }}
                    </td>
                    <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">{{ $activity->product?->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2 text-center">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $actClass }}">{{ $actLabel }}</span>
                    </td>
                    <td class="px-4 py-2 text-center font-semibold text-gray-900 dark:text-white">{{ $activity->quantity }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusBadge($activity->status) }}">{{ $activity->status }}</span>
                    </td>
                    <td class="px-4 py-2 text-center font-semibold text-gray-900 dark:text-white">{{ $activity->note }}</td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>