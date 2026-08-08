@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.manager-sidebar />
@endsection

@section('content')
{{-- AKTIVITAS TERBARU --}}
<div class="col-span-12 lg:col-span-4 bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5 grid grid-cols-1">
    <div class="grid grid-cols-2 items-center mb-4">
        <h4 class="font-semibold text-gray-900 dark:text-white">Aktivitas Terbaru</h4>
        <span class="text-xs text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded justify-self-end">{{ $recentActivities->count() }}</span>
    </div>

    <div class="space-y-4 overflow-y-auto max-h-80 pr-1 scrollbar-hide">
        @forelse($recentActivities->sortByDesc('date') as $activity)
        <div class="grid grid-cols-[auto_1fr] items-start gap-3">
            <div class="w-8 h-8 rounded-full grid place-items-center mt-0.5
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
        @empty
        <div class="text-center py-4 text-gray-400 text-sm">
            Belum ada aktivitas
        </div>
        @endforelse
    </div>

    <a href="#" class="block mt-4 text-center text-sm text-blue-600 hover:text-blue-800 hover:underline">
        Lihat Semua Aktivitas
    </a>
</div>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection