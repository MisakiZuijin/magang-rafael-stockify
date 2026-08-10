@props(['status'])

@php
$classes = match($status) {
'Diterima' => 'bg-green-400 text-green-900',
'Pending' => 'bg-yellow-400 text-yellow-900',
'Dikeluarkan' => 'bg-blue-400 text-blue-900',
default => 'bg-red-400 text-red-900',
};
@endphp

<span class="px-2 py-1 rounded-full text-xs font-medium {{ $classes }}">
    {{ $status }}
</span>