{{-- resources/views/components/table/badges/badge-stock.blade.php --}}
@props(['stock', 'minimum' => null])

@php
$classes = match(true) {
$stock == 0 => 'bg-gray-400 text-gray-900',
$minimum !== null && $stock <= $minimum=> 'bg-red-400 text-red-900',
    $minimum !== null && $stock <= $minimum + 10=> 'bg-yellow-400 text-yellow-900',
        $minimum === null && $stock < 10=> 'bg-red-400 text-red-900',
            $minimum === null && $stock < 20=> 'bg-yellow-400 text-yellow-900',
                default => 'bg-green-400 text-green-900',
                };
                @endphp

                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $classes }}">
                    {{ $stock }}
                </span>