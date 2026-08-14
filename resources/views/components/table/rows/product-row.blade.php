@props(['product'])

<tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
    <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $product->id }}</td>
    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $product->sku }}</td>
    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $product->categori?->name ?? 'N/A' }}</td>
    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $product->supplier?->name ?? 'N/A' }}</td>
    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
    <td class="px-4 py-3">
        <x-table.badges.badge-stock :stock="$product->stock" :minimum="$product->minimum_stock" />
    </td>
    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $product->minimum_stock }}</td>
    <td class="px-4 py-3">
        <a href="{{ route('products.show', $product->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detail</a>
    </td>
</tr>