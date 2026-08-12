{{-- resources/views/components/table/rows/report-stock-row.blade.php --}}
@props(['product'])

<tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
    <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $product->id }}</td>
    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $product->sku }}</td>
    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $product->categori?->name ?? '-' }}</td>
    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $product->supplier?->name ?? '-' }}</td>
    <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
    <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
    <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">{{ $product->stock }}</td>
    <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">{{ $product->minimum_stock }}</td>
    <td class="px-4 py-3 text-center">
        <x-table.badges.badge-stock-status :product="$product" />
    </td>
</tr>