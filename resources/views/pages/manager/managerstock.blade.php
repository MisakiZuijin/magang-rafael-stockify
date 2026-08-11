@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.manager-sidebar />
@endsection

@section('content')
<div class="lg:pb-10 min-h-screen relative z-0">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">

        {{-- Header --}}
        <x-page-header title="Kelola Stok" subtitle="Pencatatan barang masuk, keluar, dan stock opname" />

        {{-- Flash Message --}}
        @if(session('success'))
        <x-alert.flash-message type="success" :message="session('success')" />
        @endif
        @if(session('error'))
        <x-alert.flash-message type="error" :message="session('error')" />
        @endif

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-card.cards label="Masuk Hari Ini" :value="$todayIncoming->sum('quantity')" color="green" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </x-card.cards>

            <x-card.cards label="Keluar Hari Ini" :value="$todayOutgoing->sum('quantity')" color="red" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
            </x-card.cards>

            <x-card.cards label="Pending" :value="$pendingCount" color="orange" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-card.cards>
        </div>

        {{-- TABEL BARANG MASUK & KELUAR --}}
        <div class="grid grid-cols-12 gap-6">

            {{-- BARANG MASUK --}}
            <div class="col-span-12 lg:col-span-6">
                @php
                $incomingHeaders = ['ID', 'Tanggal', 'Produk', 'Qty', 'Status'];
                @endphp

                <x-table.data-table
                    :headers="$incomingHeaders"
                    title="Barang Masuk"
                    subtitle="Riwayat penerimaan barang"
                    colSpan="col-span-12"
                    maxHeight="max-h-[350px]">
                    <x-slot:headerAction>
                        <a href="{{ route('manager.transactions.create') }}?type=Masuk" class="inline-flex items-center gap-1.5 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Catat Masuk
                        </a>
                    </x-slot:headerAction>

                    @forelse($incomingTransactions as $trx)
                    <x-table.rows.manager-transaction-row :trx="$trx" />
                    @empty
                    <tr>
                        <td colspan="{{ count($incomingHeaders) }}" class="px-4 py-8 text-center text-gray-400">Belum ada barang masuk.</td>
                    </tr>
                    @endforelse
                </x-table.data-table>
            </div>

            {{-- BARANG KELUAR --}}
            <div class="col-span-12 lg:col-span-6">
                @php
                $outgoingHeaders = ['ID', 'Tanggal', 'Produk', 'Qty', 'Status'];
                @endphp

                <x-table.data-table
                    :headers="$outgoingHeaders"
                    title="Barang Keluar"
                    subtitle="Riwayat pengeluaran barang"
                    colSpan="col-span-12"
                    maxHeight="max-h-[350px]">
                    <x-slot:headerAction>
                        <a href="{{ route('manager.transactions.create') }}?type=Keluar" class="inline-flex items-center gap-1.5 bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Catat Keluar
                        </a>
                    </x-slot:headerAction>

                    @forelse($outgoingTransactions as $trx)
                    <x-table.rows.manager-transaction-row :trx="$trx" />
                    @empty
                    <tr>
                        <td colspan="{{ count($outgoingHeaders) }}" class="px-4 py-8 text-center text-gray-400">Belum ada barang keluar.</td>
                    </tr>
                    @endforelse
                </x-table.data-table>
            </div>

        </div>

        {{-- STOCK OPNAME --}}
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-12">
                <x-form.stock-opname :products="$products" :action="route('manager.stock.opname')" />
            </div>
        </div>

    </div>
</div>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection