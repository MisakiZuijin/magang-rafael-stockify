@props([
'title' => 'Distribusi Stok (vs Minimum)',
'canvasId' => 'stockDoughnut',
'height' => 'h-64',
'colSpan' => 'col-span-12 lg:col-span-4',
'items' => [],
])

<div class="{{ $colSpan }} bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5">
    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">{{ $title }}</h4>
    <div class="relative {{ $height }} w-full grid place-items-center">
        <canvas id="{{ $canvasId }}"></canvas>
    </div>
    @if(count($items) > 0)
    <div class="mt-4 space-y-2 text-sm">
        @foreach($items as $item)
        <div class="grid grid-cols-2 items-center">
            <span class="text-gray-500 dark:text-gray-400">{{ $item['label'] }}</span>
            <span class="font-semibold {{ $item['colorClass'] }} justify-self-end">{{ $item['value'] }}</span>
        </div>
        @endforeach
    </div>
    @endif
</div>