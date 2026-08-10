@props(['transaction', 'showAction' => true])

<tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
    <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $transaction->id }}</td>
    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $transaction->product?->name ?? 'N/A' }}</td>
    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $transaction->user?->name ?? 'N/A' }}</td>
    <td class="px-4 py-3">
        <x-table.badges.badge-type :type="$transaction->type" />
    </td>
    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $transaction->quantity }}</td>
    <td class="px-4 py-3">
        <x-table.badges.badge-status :status="$transaction->status" />
    </td>
    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $transaction->note ?? '-' }}</td>

    @if($showAction)
    <td class="px-4 py-3">
        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detail</button>
    </td>
    @endif
</tr>