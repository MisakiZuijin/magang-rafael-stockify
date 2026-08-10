{{-- resources/views/components/admin/full-list.blade.php --}}
@props([
'title',
'subtitle' => null,
'backRoute' => null,
'createRoute' => null,
'createLabel' => 'Tambah Data',
'searchRoute' => null,
'searchPlaceholder' => 'Cari data...',
'searchQuery' => '',
'headers' => [],
'items',
'emptyMessage' => 'Tidak ada data.',
])

<div class="lg:pb-10 min-h-screen relative z-0">
    <div class="p-4 sm:p-6 lg:p-8">

        @if(session('success'))
        <x-alert.flash-message type="success" :message="session('success')" />
        @endif
        @if(session('error'))
        <x-alert.flash-message type="error" :message="session('error')" />
        @endif

        {{-- Header --}}
        <div class="mb-6">
            @if($backRoute)
            <a href="{{ $backRoute }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-2 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            @endif
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
            @if($subtitle)
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $subtitle }}</p>
            @endif
        </div>

        {{-- Toolbar --}}
        <div class="mb-6 flex flex-col lg:flex-row gap-3 justify-between items-center bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <div class="w-full lg:w-auto lg:flex-1 max-w-md">
                @if($searchRoute)
                <form action="{{ $searchRoute }}" method="GET" class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ $searchQuery }}"
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition"
                        placeholder="{{ $searchPlaceholder }}">
                </form>
                @endif
            </div>

            <div class="flex gap-2 w-full lg:w-auto justify-end">
                @if($createRoute)
                <a href="{{ $createRoute }}" class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ $createLabel }}
                </a>
                @endif
            </div>
        </div>

        {{-- Table --}}
        <x-table.data-table
            :headers="$headers"
            colSpan="col-span-12"
            maxHeight="max-h-[70vh]">
            {{ $slot }}
        </x-table.data-table>

        {{-- Pagination --}}
        @if($items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages())
        <div class="mt-6">
            {{ $items->links() }}
        </div>
        @endif

        @if($items->count() === 0)
        <div class="text-center py-12 text-gray-400 text-sm border border-dashed border-gray-300 dark:border-gray-600 rounded-lg mt-4">
            {{ $emptyMessage }}
        </div>
        @endif

    </div>
</div>