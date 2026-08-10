{{-- resources/views/components/form/form-actions.blade.php --}}
@props([
'submitLabel' => 'Simpan',
'cancelLabel' => 'Batal',
'cancelUrl' => '#'
])

<div class="flex gap-3">
    <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition font-medium focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700">
        {{ $submitLabel }}
    </button>
    <a href="{{ $cancelUrl }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-200 transition font-medium dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
        {{ $cancelLabel }}
    </a>
</div>