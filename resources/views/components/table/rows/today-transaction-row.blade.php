{{-- resources/views/components/table/rows/today-transaction-row.blade.php --}}
@props(['trx'])

@php
$statusClasses = match($trx->status) {
'Diterima' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
'Pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
'Dikeluarkan' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
'Ditolak' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
};
@endphp

<tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-center">
    <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $trx->id }}</td>
    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $trx->product?->name ?? 'N/A' }}</td>
    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $trx->user?->name ?? 'N/A' }}</td>
    <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">{{ $trx->quantity }}</td>
    <td class="px-4 py-3 text-center">
        <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $statusClasses }}">{{ $trx->status }}</span>
    </td>
    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs whitespace-nowrap">
        {{ $trx->created_at?->diffForHumans() ?? ($trx->date ? \Carbon\Carbon::parse($trx->date)->format('H:i') : '-') }}
    </td>
</tr>