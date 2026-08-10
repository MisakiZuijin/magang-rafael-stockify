{{-- resources/views/components/table/badges/badge-role.blade.php --}}
@props(['role'])

@php
$classes = match($role) {
'Admin' => 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300',
'Manager Gudang' => 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300',
'Staff Gudang' => 'bg-teal-100 text-teal-700 dark:bg-teal-900 dark:text-teal-300',
default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
};
@endphp

<span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $classes }}">
    {{ $role }}
</span>