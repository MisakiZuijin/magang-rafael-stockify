{{-- resources/views/components/table/rows/manager-product-row.blade.php --}}
@props(['product'])

<tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-center">
    <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $product->id }}</td>
    <td class="px-4 py-3">
        @if($product->image)
        <img src="{{ asset('images/' . $product->image) }}" class="w-10 h-10 rounded-lg object-cover">
        @else
        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        @endif
    </td>
    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $product->categori?->name ?? 'N/A' }}</td>
    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $product->supplier?->name ?? 'N/A' }}</td>
    <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
    <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
    <td class="px-4 py-3 text-center">
        <x-table.badges.badge-stock :stock="$product->stock" />
    </td>
    <td class="px-4 py-3 font-medium text-gray-600 dark:text-gray-400">{{ $product->description }}</td>
    <td class="px-4 py-3 text-center">
        <div class="grid grid-flow-col auto-cols-max items-center justify-center gap-2">
            {{-- Detail --}}
            <a href="{{ route('manager.products.show', $product->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Detail">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </a>
        </div>
    </td>
</tr>