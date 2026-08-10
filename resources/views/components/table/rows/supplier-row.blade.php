{{-- resources/views/components/table/rows/supplier-row.blade.php --}}
@props(['supplier'])

<tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
    <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $supplier->id }}</td>
    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $supplier->name }}</td>
    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $supplier->email ?? '-' }}</td>
    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $supplier->phone ?? '-' }}</td>
    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ $supplier->address ?? '-' }}</td>
    <td class="px-4 py-3 text-center">
        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
            {{ $supplier->products_count ?? 0 }}
        </span>
    </td>
    <td class="px-4 py-3 text-center">
        <div class="grid grid-flow-col auto-cols-max items-center justify-center gap-2">
            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Edit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </a>
            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Yakin hapus supplier ini? Semua produk terkait mungkin terpengaruh.')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
        </div>
    </td>
</tr>