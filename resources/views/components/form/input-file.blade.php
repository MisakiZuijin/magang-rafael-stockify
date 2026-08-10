{{-- resources/views/components/form/input-file.blade.php --}}
@props([
'name',
'label',
'accept' => 'image/*',
'hint' => '',
'previewUrl' => null,
'previewSize' => 'w-20 h-20',
'fallbackIcon' => null
])

<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $label }}</label>
    <div class="flex items-center gap-4">
        <div class="{{ $previewSize }} rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 grid place-items-center overflow-hidden">
            @if($previewUrl)
            <img src="{{ $previewUrl }}?t={{ time() }}" alt="{{ $label }}" class="w-full h-full object-contain p-1">
            @else
            {{ $fallbackIcon }}
            @endif
        </div>
        <div class="flex-1">
            <input type="file" name="{{ $name }}" accept="{{ $accept }}"
                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
            @if($hint)
            <p class="text-xs text-gray-400 mt-1">{{ $hint }}</p>
            @endif
            @error($name)
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>