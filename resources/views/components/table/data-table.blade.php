{{-- resources/views/components/table/data-table.blade.php --}}
@props([
'headers' => [],
'title' => null,
'subtitle' => null,
'showViewAll' => false,
'viewAllRoute' => null,
'maxHeight' => null,
'colSpan' => 'col-span-12 lg:col-span-8'
])

<div class="{{ $colSpan }} bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5 grid grid-cols-1">

    @if($title || $showViewAll || isset($headerAction) || $subtitle || $viewAllRoute)
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-3">
        <div>
            @if($title)
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h4>
            @endif
            @if($subtitle)
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="flex items-center gap-2 justify-end">
            {{ $headerAction ?? '' }}

            @if($viewAllRoute)
            <a href="{{ $viewAllRoute }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
            @elseif($showViewAll)
            <button class="text-sm text-blue-600 hover:underline">Lihat Semua</button>
            @endif
        </div>
    </div>
    @endif

    <div class="overflow-x-auto overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700 {{ $maxHeight ?? '' }} scrollbar-hide">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium sticky top-0 z-10">
                <tr>
                    @foreach($headers as $header)
                    <th scope="col" class="px-4 py-3 whitespace-nowrap">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>