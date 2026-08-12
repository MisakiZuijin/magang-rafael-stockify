@props([
'headers' => [],
'title' => null,
'subtitle' => null,
'showViewAll' => false,
'viewAllRoute' => null,
'height' => null,
'colSpan' => 'col-span-12 lg:col-span-8',
'sortColumn' => null,
'sortDirection' => 'asc',
'searchable' => false,
'searchPlaceholder' => 'Cari...',
'currentSearch' => '',
'tableId' => 'table-' . uniqid(),
'searchParam' => 'search',
'sortParam' => 'sort',
'directionParam' => 'direction',
])

@php
$parsedHeaders = collect($headers)->map(function($h) {
if (is_array($h)) {
return [
'label' => $h['label'] ?? '',
'key' => $h['key'] ?? null,
'sortable' => $h['sortable'] ?? isset($h['key']),
];
}
return ['label' => $h, 'key' => null, 'sortable' => false];
})->toArray();
@endphp

<div id="{{ $tableId }}" class="data-table-wrapper {{ $colSpan }} bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5 grid grid-cols-1">

    @if($title || $showViewAll || isset($headerAction) || $subtitle || $viewAllRoute || $searchable)
    <div class="flex flex-col gap-4 mb-4">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
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

        @if($searchable)
        <form action="{{ request()->url() }}" method="GET" class="data-table-search-form w-full sm:max-w-sm">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                {{-- FIX #2: pakai $searchParam langsung, bukan fallback ke 'search' --}}
                <input type="text" name="{{ $searchParam }}" value="{{ $currentSearch }}"
                    class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    placeholder="{{ $searchPlaceholder }}">

                @if($currentSearch)
                {{-- FIX #3: tombol X hapus parameter yang sesuai ($searchParam), bukan hardcoded 'search' --}}
                <a href="{{ request()->fullUrlWithQuery([$searchParam => null]) }}"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-red-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
                @endif
            </div>

            {{-- FIX #4: exclude $searchParam (bukan hardcoded 'search') supaya tidak duplicate/conflict antar tabel --}}
            @foreach(request()->except([$searchParam, 'page']) as $key => $value)
            @if(is_array($value))
            @foreach($value as $v)
            @if($v !== null)
            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
            @endif
            @endforeach
            @elseif($value !== null)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
            @endforeach
        </form>
        @endif
    </div>
    @endif

    <div class="overflow-x-auto overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700 {{ $height ?? '' }} scrollbar-hide">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium sticky top-0 z-10">
                <tr>
                    @foreach($parsedHeaders as $header)
                    <th scope="col" class="px-4 py-3 whitespace-nowrap select-none">
                        @if($header['sortable'] && $header['key'])
                        @php
                        $isActive = $sortColumn === $header['key'];
                        $newDirection = ($isActive && $sortDirection === 'asc') ? 'desc' : 'asc';
                        $sortUrl = request()->fullUrlWithQuery(['sort' => $header['key'], 'direction' => $newDirection]);
                        @endphp
                        <a href="{{ $sortUrl }}"
                            class="data-table-sort-link group inline-flex items-center gap-1.5 hover:text-blue-600 dark:hover:text-blue-400 transition-colors {{ $isActive ? 'text-blue-600 dark:text-blue-400' : '' }}">
                            <span>{{ $header['label'] }}</span>
                            <span class="inline-flex items-center">
                                @if($isActive && $sortDirection === 'asc')
                                <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                                </svg>
                                @elseif($isActive && $sortDirection === 'desc')
                                <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                                @else
                                <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                                @endif
                            </span>
                        </a>
                        @else
                        <span>{{ $header['label'] }}</span>
                        @endif
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>