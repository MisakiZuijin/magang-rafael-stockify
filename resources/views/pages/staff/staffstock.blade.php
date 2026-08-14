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
        <x-page-header title="Konfirmasi Stok" subtitle="Verifikasi barang masuk dan keluar" />

        {{-- Flash Message --}}
        @if(session('success'))
        <x-alert.flash-message type="success" :message="session('success')" />
        @endif
        @if(session('error'))
        <x-alert.flash-message type="error" :message="session('error')" />
        @endif

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <x-card.cards label="Masuk Pending" :value="$incomingPending->count()" color="green" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </x-card.cards>

            <x-card.cards label="Keluar Pending" :value="$outgoingPending->count()" color="red" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
            </x-card.cards>

            <x-card.cards label="Total Perlu Dicek" :value="$incomingPending->count() + $outgoingPending->count()" color="orange" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </x-card.cards>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- DAFTAR BARANG MASUK PENDING --}}
            @php
            $incomingHeaders = ['ID', 'Tanggal', 'Produk', 'Pengaju', 'Qty', 'Catatan', 'Aksi'];
            @endphp

            <x-table.data-table
                :headers="$incomingHeaders"
                title="Barang Masuk — Menunggu Konfirmasi"
                subtitle="Verifikasi penerimaan barang dari supplier atau pengajuan manager"
                colSpan="col-span-12"
                maxHeight="max-h-[350px]">
                <x-slot:headerAction>
                    <span class="text-xs bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-3 py-1 rounded-full">
                        {{ $incomingPending->count() }} Pending
                    </span>
                </x-slot:headerAction>

                @forelse($incomingPending as $trx)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-center">
                    <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $trx->id }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                        {{ $trx->date ? \Carbon\Carbon::parse($trx->date)->format('d M Y') : '-' }}
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $trx->product?->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $trx->user?->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">{{ $trx->quantity }}</td>
                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 max-w-xs truncate">{{ $trx->note ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <form action="{{ route('staff.transactions.confirm', $trx->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" onclick="return confirm('Yakin terima barang masuk ini? Stok akan bertambah.')" class="flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition" title="Terima">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Terima
                                </button>
                            </form>
                            <form action="{{ route('staff.transactions.reject', $trx->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" onclick="return confirm('Yakin tolak barang masuk ini?')" class="flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition" title="Tolak">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($incomingHeaders) }}" class="px-4 py-8 text-center text-gray-400">Tidak ada barang masuk yang menunggu konfirmasi.</td>
                </tr>
                @endforelse
            </x-table.data-table>

            {{-- DAFTAR BARANG KELUAR PENDING --}}
            @php
            $outgoingHeaders = ['ID', 'Tanggal', 'Produk', 'Pengaju', 'Qty', 'Catatan', 'Aksi'];
            @endphp

            <x-table.data-table
                :headers="$outgoingHeaders"
                title="Barang Keluar — Menunggu Konfirmasi"
                subtitle="Verifikasi pengeluaran barang dari pengajuan manager"
                colSpan="col-span-12"
                maxHeight="max-h-[350px]">
                <x-slot:headerAction>
                    <span class="text-xs bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 px-3 py-1 rounded-full">
                        {{ $outgoingPending->count() }} Pending
                    </span>
                </x-slot:headerAction>

                @forelse($outgoingPending as $trx)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-center">
                    <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $trx->id }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                        {{ $trx->date ? \Carbon\Carbon::parse($trx->date)->format('d M Y') : '-' }}
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $trx->product?->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $trx->user?->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">{{ $trx->quantity }}</td>
                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 max-w-xs truncate">{{ $trx->note ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <form action="{{ route('staff.transactions.confirm', $trx->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" onclick="return confirm('Yakin keluarkan barang ini? Stok akan berkurang.')" class="flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition" title="Keluarkan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Keluarkan
                                </button>
                            </form>
                            <form action="{{ route('staff.transactions.reject', $trx->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" onclick="return confirm('Yakin tolak pengeluaran ini?')" class="flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition" title="Tolak">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($outgoingHeaders) }}" class="px-4 py-8 text-center text-gray-400">Tidak ada barang keluar yang menunggu konfirmasi.</td>
                </tr>
                @endforelse
            </x-table.data-table>
        </div>

    </div>
</div>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection