@props([
'title' => 'Grafik Stok vs Minimum',
'badge' => 'Perbandingan per Produk',
'canvasId' => 'stockChart',
'height' => 'h-80',
'colSpan' => 'col-span-12 lg:col-span-8',
'barWidthPerItem' => 80,
'itemCount' => 0,
])

@php
$minCanvasWidth = max(800, $itemCount * $barWidthPerItem);
@endphp

<div class="{{ $colSpan }} bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5">
    <div class="grid grid-cols-2 items-center mb-4">
        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $title }}</h4>
        @if($badge)
        <span class="text-xs text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded justify-self-end">{{ $badge }}</span>
        @endif
    </div>

    <div class="overflow-x-auto pb-2 scrollbar-hide">
        <div class="relative {{ $height }} chart-scroll-wrapper" data-min-width="{{ $minCanvasWidth }}">
            <canvas id="{{ $canvasId }}"></canvas>
        </div>
    </div>
</div>