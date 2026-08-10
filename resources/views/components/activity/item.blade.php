@props(['activity'])

<div class="grid grid-cols-[auto_1fr] items-start gap-3">
    {{-- Icon --}}
    <div class="w-8 h-8 rounded-full grid place-items-center mt-0.5 shrink-0
        {{ $activity->type == 'Masuk' ? 'bg-green-400 text-green-900' : 'bg-red-400 text-red-900' }}">
        @if($activity->type == 'Masuk')
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
        @else
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
        @endif
    </div>

    {{-- Content --}}
    <div class="min-w-0">
        <p class="text-sm text-gray-900 dark:text-white font-medium truncate">
            {{ $activity->user?->name ?? 'User' }}
            <span class="text-gray-500 dark:text-gray-400 font-normal">
                {{ $activity->type == 'in' ? 'menambah' : 'mengurangi' }} stok
            </span>
            <span class="font-medium text-gray-900 dark:text-white">{{ $activity->product?->name ?? 'Produk' }}</span>
        </p>
        <div class="grid grid-flow-col auto-cols-max items-center gap-2 mt-1">
            <span class="text-xs text-gray-400">
                {{ $activity->created_at?->diffForHumans() ?? '-' }}
            </span>
            <span class="text-xs px-1.5 py-0.5 rounded
                {{ $activity->type == 'Masuk' ? 'bg-green-400 text-green-900' : 'bg-red-400 text-red-900' }}">
                {{ $activity->type }}
            </span>
            <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                {{ $activity->quantity }} unit
            </span>
        </div>
    </div>
</div>