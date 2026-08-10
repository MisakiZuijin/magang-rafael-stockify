{{-- resources/views/components/page-header.blade.php --}}
@props(['title', 'subtitle' => null])

<div class="grid grid-cols-1 sm:grid-cols-2 sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
        @if($subtitle)
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    {{ $slot }}
</div>