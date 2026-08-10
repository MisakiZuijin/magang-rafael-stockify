{{-- resources/views/components/card/cards.blade.php --}}
@props([
'label',
'value',
'color' => 'blue',
'icon',
'colSpan' => 'col-span-3' // ← tambahkan ini
])

@php
$colors = [
'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-600'],
'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-600'],
'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600'],
'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600'],
'gray' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
];
$bg = $colors[$color]['bg'] ?? $colors['blue']['bg'];
$text = $colors[$color]['text'] ?? $colors['blue']['text'];
@endphp

<div class="{{ $colSpan }} bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5 h-full">
    <div class="grid grid-cols-2 items-center gap-4">
        <div class="min-w-0">
            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $label }}</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $value }}</p>
        </div>
        <div class="w-12 h-12 {{ $bg }} {{ $text }} rounded-full grid place-items-center justify-self-end shrink-0">
            {{ $slot }}
        </div>
    </div>
</div>