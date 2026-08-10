{{-- resources/views/components/alert/simple-alert.blade.php --}}
@props(['type' => 'success', 'message'])

@php
$classes = match($type) {
'success' => 'text-green-800 bg-green-50 dark:bg-gray-800 dark:text-green-400',
'error' => 'text-red-800 bg-red-50 dark:bg-gray-800 dark:text-red-400',
'warning' => 'text-yellow-800 bg-yellow-50 dark:bg-gray-800 dark:text-yellow-400',
default => 'text-blue-800 bg-blue-50 dark:bg-gray-800 dark:text-blue-400',
};
@endphp

<div class="p-4 mb-4 text-sm rounded-lg {{ $classes }}" role="alert">
    {{ $message }}
</div>