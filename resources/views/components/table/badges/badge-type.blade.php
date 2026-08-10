@props(['type'])

@php
$classes = $type === 'Masuk'
? 'bg-green-400 text-green-900'
: 'bg-red-400 text-red-900';
@endphp

<span class="px-2 py-1 rounded-full text-xs font-medium {{ $classes }}">
    {{ $type }}
</span>