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
</tr>