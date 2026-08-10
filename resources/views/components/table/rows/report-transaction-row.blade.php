{{-- resources/views/components/table/rows/report-transaction-row.blade.php --}}
@props(['trx'])

@php
$typeClasses = $trx->type == 'Masuk'
? 'bg-green-400 text-green-900'
: 'bg-red-400 text-red-900';

$statusClasses = match($trx->status) {
'Diterima' => 'bg-green-400 text-green-900',
'Pending' => 'bg-yellow-400 text-yellow-900',
'Dikeluarkan' => 'bg-blue-400 text-blue-900',
default => 'bg-red-400 text-red-900',
};
@endphp

<tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
    <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $trx->id }}</td>
    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 whitespace-nowrap">
        {{ $trx->date ? \Carbon\Carbon::parse($trx->date)->format('d M Y') : '-' }}
    </td>
    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $trx->product?->name ?? 'N/A' }}</td>
    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $trx->user?->name ?? 'N/A' }}</td>
    <td class="px-4 py-3 text-center">
        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $typeClasses }}">{{ $trx->type }}</span>
    </td>
    <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">{{ $trx->quantity }}</td>
    <td class="px-4 py-3 text-center">
        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClasses }}">{{ $trx->status }}</span>
    </td>
    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 max-w-xs truncate">{{ $trx->note ?? '-' }}</td>
</tr>