{{-- resources/views/components/form/input-text.blade.php --}}
@props([
'name',
'label',
'value' => '',
'type' => 'text',
'placeholder' => '',
'required' => false
])

<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        {{ $label }}
        @if($required)
        <span class="text-red-500">*</span>
        @endif
    </label>
    <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition"
        placeholder="{{ $placeholder }}">
    @error($name)
    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>