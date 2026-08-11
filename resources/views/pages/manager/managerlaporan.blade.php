@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.manager-sidebar />
@endsection

@section('content')

@php
$chartData = [
'stockLabels' => $stockChart['labels'],
'stockData' => $stockChart['stock'],
'stockMinimum' => $stockChart['minimum'],
'trxLabels' => $transactionChart['labels'],
'trxMasuk' => $transactionChart['masuk'],
'trxKeluar' => $transactionChart['keluar'],
];
@endphp

<div class="lg:pb-10 min-h-screen bg-gray-900 relative z-0">
    <div class="p-4 sm:p-6 lg:p-8 gap-6">

        {{-- Header --}}
        <x-page-header title="Laporan Manajer" subtitle="Ringkasan stok dan transaksi barang" />

        {{-- FILTER PANEL --}}
        <x-form.report-filter :filters="$filters" :categories="$categories" :action="route('manager.laporan')" />

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-card.cards label="Total Produk" :value="$stockSummary['total_products']" color="blue" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </x-card.cards>

            <x-card.cards label="Stok Aman" :value="$stockSummary['stock_aman']" color="green" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
            </x-card.cards>

            <x-card.cards label="Barang Masuk (Qty)" :value="number_format($transactionSummary['total_masuk'], 0, ',', '.')" color="green" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </x-card.cards>

            <x-card.cards label="Barang Keluar (Qty)" :value="number_format($transactionSummary['total_keluar'], 0, ',', '.')" color="red" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
            </x-card.cards>
        </div>

        {{-- CHARTS ROW --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <x-charts.report-chart title="Stok per Kategori" canvasId="stockCategoryChart" />
            <x-charts.report-chart title="Transaksi Harian ({{ \Carbon\Carbon::parse($filters['start_date'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($filters['end_date'])->format('d M Y') }})" canvasId="transactionDailyChart" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- SECTION 1: LAPORAN STOK BARANG --}}
            @php
            $stockHeaders = ['ID', 'Nama Produk', 'Kategori', 'Supplier', 'Harga Beli', 'Harga Jual', 'Stok', 'Min', 'Status'];
            @endphp

            <x-table.data-table
                :headers="$stockHeaders"
                title="Laporan Stok Barang"
                subtitle="Detail stok berdasarkan filter yang dipilih"
                colSpan="col-span-12"
                maxHeight="max-h-[400px]">
                <x-slot name="headerAction">
                    <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-full">
                        {{ $stockReport->count() }} Produk
                    </span>
                </x-slot>

                @forelse($stockReport->sortBy('id') as $product)
                <x-table.rows.report-stock-row :product="$product" />
                @empty
                <tr>
                    <td colspan="{{ count($stockHeaders) }}" class="px-4 py-8 text-center text-gray-400">Tidak ada data stok yang sesuai filter.</td>
                </tr>
                @endforelse
            </x-table.data-table>

            {{-- SECTION 2: LAPORAN TRANSAKSI --}}
            @php
            $trxHeaders = ['ID', 'Tanggal', 'Produk', 'User', 'Tipe', 'Qty', 'Status', 'Catatan', 'Aksi'];
            @endphp

            <x-table.data-table
                :headers="$trxHeaders"
                title="Laporan Transaksi Barang Masuk & Keluar"
                :subtitle="'Periode: ' . \Carbon\Carbon::parse($filters['start_date'])->format('d M Y') . ' - ' . \Carbon\Carbon::parse($filters['end_date'])->format('d M Y')"
                colSpan="col-span-12"
                maxHeight="max-h-[400px]">
                <x-slot name="headerAction">
                    <div class="flex gap-2">
                        <span class="text-xs bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-3 py-1 rounded-full">Masuk: {{ $transactionSummary['count_masuk'] }}x</span>
                        <span class="text-xs bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 px-3 py-1 rounded-full">Keluar: {{ $transactionSummary['count_keluar'] }}x</span>
                    </div>
                </x-slot>

                @forelse($transactionReport->sortBy('date') as $trx)
                <x-table.rows.manager-report-transaction-row :trx="$trx" />
                @empty
                <tr>
                    <td colspan="{{ count($trxHeaders) }}" class="px-4 py-8 text-center text-gray-400">Tidak ada data transaksi pada periode ini.</td>
                </tr>
                @endforelse
            </x-table.data-table>
        </div>

    </div>
</div>

{{-- DATA UNTUK JS --}}
<script type="application/json" id="chart-data">
    @json($chartData)
</script>

@endsection

@section('footer')
<x-footer-dashboard />
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/laporan-charts.js') }}"></script>
@endpush