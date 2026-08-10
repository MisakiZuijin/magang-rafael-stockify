{{-- resources/views/components/table/rows/activity-row.blade.php --}}
@props(['activity'])

@php
$icon = match($activity->type ?? 'default') {
'create', 'insert' => '<span class="w-2 h-2 rounded-full bg-green-500"></span>',
'update', 'edit' => '<span class="w-2 h-2 rounded-full bg-blue-500"></span>',
'delete', 'destroy'=> '<span class="w-2 h-2 rounded-full bg-red-500"></span>',
'login' => '<span class="w-2 h-2 rounded-full bg-purple-500"></span>',
default => '<span class="w-2 h-2 rounded-full bg-gray-500"></span>',
};
@endphp

<tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
    <td class="px-4 py-3">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 grid place-items-center text-xs font-bold shrink-0">
                {{ strtoupper(substr($activity->user?->name ?? 'S', 0, 2)) }}
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->user?->name ?? 'System' }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->user?->role ?? '-' }}</p>
            </div>
        </div>
    </td>
    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
        {{ $activity->description ?? ($activity->note ?? '-') }}
    </td>
    <td class="px-4 py-3 text-center">
        <div class="flex items-center justify-center gap-2">
            {!! $icon !!}
            <span class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ $activity->type ?? '-' }}</span>
        </div>
    </td>
    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 whitespace-nowrap text-xs">
        {{ $activity->created_at ? \Carbon\Carbon::parse($activity->created_at)->diffForHumans() : 
           ($activity->date ? \Carbon\Carbon::parse($activity->date)->diffForHumans() : '-') }}
    </td>
</tr>