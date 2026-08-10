{{-- resources/views/components/form/settings-section.blade.php --}}
@props(['title'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ $title }}</h2>
    <div class="space-y-5">
        {{ $slot }}
    </div>
</div>