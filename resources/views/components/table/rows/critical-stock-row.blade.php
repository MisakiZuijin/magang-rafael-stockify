{{-- resources/views/components/table/rows/critical-stock-row.blade.php --}}
@props(['product'])

@php
$statusClass = $product->stock == 0
? 'bg-gray-400 text-gray-900'
: 'bg-red-400 text-red-900';
$statusLabel = $product->stock == 0 ? 'Habis' : 'Kritis';
@endphp

<tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-center">
    <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $product->id }}</td>
    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $product->sku }}</td>
    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $product->categori?->name ?? '-' }}</td>
    <td class="px-4 py-3 text-center font-semibold text-red-600 dark:text-red-400">{{ $product->stock }}</td>
    <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">{{ $product->minimum_stock }}</td>
    <td class="px-4 py-3 text-center">
        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">{{ $statusLabel }}</span>
    </td>
</tr>