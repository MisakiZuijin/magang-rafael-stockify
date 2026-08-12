@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

<!-- @section('sidebar')
<x-sidebar.staff-sidebar />
@endsection -->

@section('content')
<div class="lg:pb-10 min-h-screen dark:bg-gray-900 relative z-0">
    <div class="p-4 sm:p-6 lg:p-8">

        {{-- Header --}}
        <x-page-header title="Dashboard Staff Gudang" subtitle="Daftar tugas yang perlu diselesaikan hari ini" />

        {{-- Flash Message --}}
        @if(session('success'))
        <x-alert.flash-message type="success" :message="session('success')" />
        @endif

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <x-card.cards label="Barang Masuk Pending" :value="$taskSummary['incoming_count']" color="orange" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </x-card.cards>

            <x-card.cards label="Barang Keluar Pending" :value="$taskSummary['outgoing_count']" color="orange" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
            </x-card.cards>

            <x-card.cards label="Total Tugas" :value="$taskSummary['total_tasks']" color="blue" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </x-card.cards>
        </div>

        {{-- INFO PANEL --}}
        <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-300">Informasi</h4>
                <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">
                    Untuk melakukan konfirmasi penerimaan atau pengeluaran barang, silakan menuju halaman <strong>Staff Stock</strong> melalui sidebar.
                </p>
            </div>
        </div>

        {{-- DAFTAR TUGAS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- BARANG MASUK PERLU DIPERIKSA --}}
            <div>
                @php
                $incomingHeaders = ['ID', 'Tanggal', 'Produk', 'Qty', 'Pengaju'];
                @endphp

                <x-table.data-table
                    :headers="$incomingHeaders"
                    title="Barang Masuk - Perlu Diperiksa"
                    subtitle="Daftar barang masuk yang menunggu konfirmasi penerimaan"
                    colSpan="col-span-12"
                    maxHeight="max-h-[350px]">
                    <x-slot:headerAction>
                        <span class="text-xs bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 px-3 py-1 rounded-full">
                            {{ $incomingPending->count() }} Tugas
                        </span>
                    </x-slot:headerAction>

                    @forelse($incomingPending as $trx)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $trx->id }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                            {{ $trx->date ? \Carbon\Carbon::parse($trx->date)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $trx->product?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">{{ $trx->quantity }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $trx->user?->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($incomingHeaders) }}" class="px-4 py-8 text-center text-gray-400">Tidak ada barang masuk yang perlu diperiksa.</td>
                    </tr>
                    @endforelse
                </x-table.data-table>
            </div>

            {{-- BARANG KELUAR PERLU DIPERIKSA --}}
            <div>
                @php
                $outgoingHeaders = ['ID', 'Tanggal', 'Produk', 'Qty', 'Pengaju'];
                @endphp

                <x-table.data-table
                    :headers="$outgoingHeaders"
                    title="Barang Keluar - Perlu Diperiksa"
                    subtitle="Daftar barang keluar yang menunggu konfirmasi pengeluaran"
                    colSpan="col-span-12"
                    maxHeight="max-h-[350px]">
                    <x-slot:headerAction>
                        <span class="text-xs bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 px-3 py-1 rounded-full">
                            {{ $outgoingPending->count() }} Tugas
                        </span>
                    </x-slot:headerAction>

                    @forelse($outgoingPending as $trx)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $trx->id }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                            {{ $trx->date ? \Carbon\Carbon::parse($trx->date)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $trx->product?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">{{ $trx->quantity }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $trx->user?->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($outgoingHeaders) }}" class="px-4 py-8 text-center text-gray-400">Tidak ada barang keluar yang perlu diperiksa.</td>
                    </tr>
                    @endforelse
                </x-table.data-table>
            </div>

        </div>

    </div>
</div>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection