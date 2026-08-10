@props([
'activities',
'title' => 'Aktivitas Terbaru',
'showCount' => true,
'showViewAll' => true,
'viewAllRoute' => null,
'maxHeight' => 'max-h-80',
'colSpan' => 'col-span-12 lg:col-span-4',
])

<div class="{{ $colSpan }} bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5 grid grid-cols-1">
    <div class="grid grid-cols-2 items-center mb-4">
        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $title }}</h4>
        @if($showCount)
        <span class="text-xs text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded justify-self-end">{{ $activities->count() }}</span>
        @endif
    </div>

    <div class="space-y-4 overflow-y-auto {{ $maxHeight }} pr-1 scrollbar-hide">
        @forelse($activities as $activity)
        <x-activity.item :activity="$activity" />
        @empty
        <div class="text-center py-4 text-gray-400 text-sm">
            Belum ada aktivitas
        </div>
        @endforelse
    </div>

    @if($showViewAll)
    <a href="{{ $viewAllRoute }}" class="block mt-4 text-center text-sm text-blue-600 hover:text-blue-800 hover:underline">
        Lihat Semua Aktivitas
    </a>
    @endif
</div>