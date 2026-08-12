{{-- resources/views/components/table/rows/stock-history-row.blade.php --}}
@props(['trx'])

<tr class="bg-white border-b text-gray-800 dark:text-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($trx->date)->format('d/m/Y') }}</td>
    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
        {{ $trx->product->name ?? '-' }}
    </td>
    <td class="px-6 py-4">{{ $trx->user->name ?? '-' }}</td>
    <td class="px-6 py-4">
        <x-table.badges.badge-transaction-type :type="$trx->type" />
    </td>
    <td class="px-6 py-4">{{ $trx->quantity }}</td>
    <td class="px-6 py-4">
        <x-table.badges.badge-transaction-status :status="$trx->status" />
    </td>
    <td class="px-6 py-4">{{ $trx->note ?? '-' }}</td>
</tr>