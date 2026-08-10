{{-- resources/views/components/table/badges/badge-transaction-status.blade.php --}}
@props(['status'])

@php
$classes = match($status) {
'Diterima' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
'Dikeluarkan' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
'Ditolak' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
};
@endphp

<span class="{{ $classes }} text-xs font-medium px-2.5 py-0.5 rounded">
    {{ $status }}
</span>