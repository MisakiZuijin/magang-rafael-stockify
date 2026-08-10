{{-- resources/views/components/form/input-textarea.blade.php --}}
@props([
'name',
'label',
'value' => '',
'rows' => 3,
'placeholder' => ''
])

<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $label }}</label>
    <textarea name="{{ $name }}" rows="{{ $rows }}"
        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition"
        placeholder="{{ $placeholder }}">{{ old($name, $value) }}</textarea>
    @error($name)
    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>