{{-- resources/views/components/table/badges/badge-stock-status.blade.php --}}
@props(['product'])

@php
$classes = match(true) {
$product->stock == 0 => 'bg-gray-400 text-gray-900',
$product->stock <= $product->minimum_stock => 'bg-red-400 text-red-900',
    default => 'bg-green-400 text-green-900',
    };

    $label = match(true) {
    $product->stock == 0 => 'Habis',
    $product->stock <= $product->minimum_stock => 'Kritis',
        default => 'Aman',
        };
        @endphp

        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $classes }}">{{ $label }}</span>