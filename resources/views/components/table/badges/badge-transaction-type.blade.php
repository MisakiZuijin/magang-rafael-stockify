{{-- resources/views/components/table/badges/badge-transaction-type.blade.php --}}
@props(['type'])

@php
$classes = $type === 'Masuk'
? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
@endphp

<span class="{{ $classes }} text-xs font-medium px-2.5 py-0.5 rounded">
    {{ $type }}
</span>