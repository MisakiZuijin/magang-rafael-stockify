{{-- resources/views/components/show/detail.blade.php --}}
@props([
'title',
'subtitle' => null,
'backRoute' => null,
'editRoute' => null,
'deleteRoute' => null,
'fields' => [],
])

<div class="lg:pb-10 min-h-screen relative z-0">
    <div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto">

        {{-- Tombol Kembali --}}
        @if($backRoute)
        <a href="{{ $backRoute }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            {{-- Header --}}
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
                    @if($subtitle)
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $subtitle }}</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    @if($editRoute)
                    <a href="{{ $editRoute }}" class="inline-flex items-center gap-1.5 bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-yellow-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    @endif
                    @if($deleteRoute)
                    <form action="{{ $deleteRoute }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Body Fields --}}
            <div class="p-6 space-y-1">
                @foreach($fields as $field)
                @php
                $label = $field['label'] ?? '';
                $value = $field['value'] ?? null;
                $type = $field['type'] ?? 'text';
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $label }}</div>
                    <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white">

                        @if($type === 'image' && $value)
                        <img src="{{ $value }}" alt="{{ $label }}" class="w-32 h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                        @elseif($type === 'image' && !$value)
                        <div class="w-32 h-32 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @elseif($type === 'money')
                        <span class="font-mono">Rp {{ number_format($value ?? 0, 0, ',', '.') }}</span>
                        @elseif($type === 'stock')
                        <x-table.badges.badge-stock :stock="$value" />
                        @elseif($type === 'badge')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $field['class'] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ $value }}
                        </span>
                        @elseif($type === 'date' && $value)
                        {{ \Carbon\Carbon::parse($value)->format('d M Y') }}
                        @else
                        {{ $value ?? '-' }}
                        @endif

                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>