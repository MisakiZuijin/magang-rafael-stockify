{{-- resources/views/components/charts/chart-card.blade.php --}}
@props(['title', 'canvasId', 'height' => 'h-72'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">{{ $title }}</h4>
    <div class="relative {{ $height }}">
        <canvas id="{{ $canvasId }}"></canvas>
    </div>
</div>