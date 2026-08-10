{{-- resources/views/components/table/rows/minimum-stock-row.blade.php --}}
@props(['product'])

<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
        {{ $product->name }}
        @if($product->minimum_stock > 0 && $product->stock <= $product->minimum_stock)
            <span class="ml-2 text-xs text-red-600 font-semibold">KRITIS</span>
            @endif
    </td>
    <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $product->stock }}</td>
    <td class="px-4 py-3">
        <form action="{{ route('stock.minimum.update', $product->id) }}" method="POST" class="grid grid-cols-[1fr_auto] items-center gap-2">
            @csrf
            <input type="number" name="minimum_stock" value="{{ $product->minimum_stock }}" min="0"
                class="no-spinner w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-blue-600 dark:hover:bg-blue-700">
                Simpan
            </button>
        </form>
    </td>
</tr>