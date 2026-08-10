{{-- resources/views/components/alert/flash-message.blade.php --}}
@props(['type' => 'success', 'message'])

@php
$colors = match($type) {
'success' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'text' => 'text-green-700', 'button' => 'text-green-600 hover:text-green-800'],
'error' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-700', 'button' => 'text-red-600 hover:text-red-800'],
default => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-700', 'button' => 'text-blue-600 hover:text-blue-800'],
};
@endphp

<div class="mb-4 {{ $colors['bg'] }} border {{ $colors['border'] }} {{ $colors['text'] }} px-4 py-3 rounded-lg flex items-center justify-between">
    <span class="text-sm font-medium">{{ $message }}</span>
    <button onclick="this.parentElement.remove()" class="{{ $colors['button'] }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>