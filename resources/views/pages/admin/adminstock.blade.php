@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

<!-- @section('sidebar')
<x-sidebar.admin-sidebar />
@endsection -->

@section('content')
<div class="lg:pb-10 min-h-screen relative z-0">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">

        {{-- Header --}}
        <x-page-header title="Daftar Stock" subtitle="Kelola semua stock yang tersedia di sistem" />

        {{-- Flash Message --}}
        @if(session('success'))
        <x-alert.simple-alert type="success" :message="session('success')" />
        @endif

        {{-- Alert Low Stock --}}
        @if($lowStockProducts->count() > 0)
        <x-alert.low-stock :count="$lowStockProducts->count()" />
        @endif

        {{-- Stats Cards --}}
        <div class="grid grid-cols-12 md:grid-cols-12 gap-4">
            <x-card.cards label="Total Transaksi" :value="$transactions->count()" color="blue">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </x-card.cards>

            <x-card.cards label="Stock Masuk" :value="$transactions->where('type', 'Masuk')->sum('quantity')" color="green">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </x-card.cards>

            <x-card.cards label="Stock Keluar" :value="$transactions->where('type', 'Keluar')->sum('quantity')" color="red">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
            </x-card.cards>

            <x-card.cards label="Pending" :value="$transactions->where('status', 'Pending')->count()" color="orange">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-card.cards>
        </div>

        {{-- Riwayat Transaksi --}}
        @php
        $historyHeaders = [
        ['key' => 'date', 'label' => 'Tanggal'],
        ['key' => 'product', 'label' => 'Produk'],
        ['key' => 'user', 'label' => 'User'],
        ['key' => 'type', 'label' => 'Tipe'],
        ['key' => 'quantity', 'label' => 'Qty'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'note', 'label' => 'Keterangan'],
        ];
        @endphp

        <x-table.data-table
            tableId="tabel-riwayat-transaksi"
            :headers="$historyHeaders"
            title="Riwayat Transaksi"
            subtitle="Semua aktivitas barang masuk dan keluar"
            colSpan="col-span-12"
            height="h-[350px]"
            sortColumn="{{ $sortColumn }}"
            sortDirection="{{ $sortDirection }}"
            :searchable="true"
            searchPlaceholder="Cari transaksi..."
            currentSearch="{{ $search }}"
            viewAllRoute="{{ route('transactions.full') }}"
            showViewAll>

            @forelse($transactions->sortByDesc('date') as $trx)
            <x-table.rows.stock-history-row :trx="$trx" />
            @empty
            <tr>
                <td colspan="{{ count($historyHeaders) }}" class="px-4 py-8 text-center text-gray-400">
                    @if($search)
                    Tidak ada hasil untuk "{{ $search }}".
                    @else
                    Belum ada transaksi.
                    @endif
                </td>
            </tr>
            @endforelse
        </x-table.data-table>

        {{-- Grid: Stock Opname & Minimum Stock --}}
        <div class="grid grid-cols-12 gap-6">

            {{-- Stock Opname --}}
            <div class="col-span-12 lg:col-span-5">
                <x-form.stock-opname
                    :products="$products"
                    maxHeight="max-h-100" />
            </div>

            {{-- Pengaturan Stock Minimum --}}
            <div class="col-span-12 lg:col-span-7">
                @php
                $minStockHeaders = [
                ['key' => 'product_name', 'label' => 'Produk'],
                ['key' => 'stock', 'label' => 'Stock'],
                ['key' => 'minimum_stock', 'label' => 'Minimum'],
                ];
                @endphp

                <x-table.data-table
                    tableId="tabel-minimum-stock"
                    :headers="$minStockHeaders"
                    title="Pengaturan Stock Minimum"
                    subtitle="Atur batas minimum setiap produk"
                    colSpan="col-span-12"
                    height="h-[260px]"
                    sortColumn="{{ $sortColumn }}"
                    sortDirection="{{ $sortDirection }}"
                    :searchable="true"
                    searchPlaceholder="Cari produk..."
                    searchParam="search_min"
                    currentSearch="{{ $searchMin }}">

                    @forelse($productsSorted as $product)
                    <x-table.rows.minimum-stock-row :product="$product" />
                    @empty
                    <tr>
                        <td colspan="{{ count($minStockHeaders) }}" class="px-4 py-8 text-center text-gray-400">
                            @if($searchMin)
                            Tidak ada hasil untuk "{{ $searchMin }}".
                            @else
                            Tidak ada produk.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </x-table.data-table>
            </div>
        </div>

    </div>

</div>
</div>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection