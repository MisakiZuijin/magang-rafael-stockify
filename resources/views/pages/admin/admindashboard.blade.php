@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

<!-- @section('sidebar')
<x-sidebar.admin-sidebar />
@endsection -->

@section('content')

@php
$chartData = [
'productNames' => $products->pluck('name')->values(),
'productStocks' => $products->pluck('stock')->values(),
'productMinimums' => $products->pluck('minimum_stock')->values(),
'stockAman' => $products->filter(fn($p) => $p->stock > $p->minimum_stock)->count(),
'stockKritis' => $products->filter(fn($p) => $p->stock <= $p->minimum_stock && $p->stock > 0)->count(),
    'stockHabis' => $products->where('stock', 0)->count(),
    ];
    @endphp

    <div class="lg:pb-10 min-h-screen relative z-0">
        <div class="p-4 sm:p-6 lg:p-8">

            <x-page-header title="Dashboard" subtitle="Selamat datang, {{ auth()->user()->name }}!" />

            <div class="grid grid-cols-12 gap-4 lg:gap-6">
                <div class="grid col-span-12 grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- CARD 1 --}}
                    <x-card.cards label="Total Produk" :value="$products->count()" color="blue" colSpan="col-span-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </x-card.cards>

                    {{-- CARD 2 --}}
                    <x-card.cards label="Total Stok" :value="$products->sum('stock')" color="green" colSpan="col-span-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </x-card.cards>

                    {{-- CARD 3 --}}
                    <x-card.cards label="Stok Kritis" :value="$chartData['stockKritis']" color="red" colSpan="col-span-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </x-card.cards>

                    {{-- CARD 4 --}}
                    <x-card.cards label="Total Transaksi" :value="$transactions->count()" color="purple" colSpan="col-span-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </x-card.cards>

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
                </div>

                {{-- BAR CHART --}}
                <x-charts.bar-chart
                    title="Grafik Stok vs Minimum"
                    badge="Perbandingan per Produk"
                    canvasId="stockChart"
                    height="h-80"
                    :itemCount="count($chartData['productNames'] ?? [])"
                    :barWidthPerItem="60" />

                {{-- DOUGHNUT CHART --}}
                @php
                $doughnutItems = [
                ['label' => 'Stok Aman (> min)', 'value' => $chartData['stockAman'], 'colorClass' => 'text-green-600'],
                ['label' => 'Stok Kritis (≤ min)', 'value' => $chartData['stockKritis'], 'colorClass' => 'text-red-600'],
                ['label' => 'Stok Habis (0)', 'value' => $chartData['stockHabis'], 'colorClass' => 'text-gray-600'],
                ];
                @endphp

                <x-charts.doughnut-chart
                    title="Distribusi Stok (vs Minimum)"
                    canvasId="stockDoughnut"
                    height="h-64"
                    :items="$doughnutItems" />

                {{-- TABEL TRANSAKSI --}}
                @php
                $headers = [
                ['key' => 'id', 'label' => 'ID'],
                ['key' => 'product', 'label' => 'Nama Product'],
                ['key' => 'user', 'label' => 'Nama User'],
                ['key' => 'type', 'label' => 'Type'],
                ['key' => 'quantity', 'label' => 'Quantity'],
                ['key' => 'status', 'label' => 'Status'],
                'Note',
                'Aksi',
                ];
                @endphp

                <x-table.data-table
                    tableId="tabel-admin-transaksi"
                    :headers="$headers"
                    title="Data Transaksi"
                    viewAllRoute="{{ route('transactions.full') }}"
                    showViewAll
                    height="h-80"
                    sortColumn="{{ $sortColumn }}"
                    sortDirection="{{ $sortDirection }}"
                    :searchable="true"
                    searchPlaceholder="Cari transaksi..."
                    currentSearch="{{ $search }}">
                    @forelse($transactions->sortByDesc('id') as $transaction)
                    <x-table.rows.transaction-row :transaction="$transaction" />
                    @empty
                    <tr>
                        <td colspan="{{ count($headers) }}" class="px-4 py-8 text-center text-gray-400">
                            @if($search)
                            Tidak ada hasil untuk "{{ $search }}".
                            @else
                            Tidak ada data transaksi.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </x-table.data-table>

                {{-- AKTIVITAS TERBARU --}}
                <x-activity.list
                    :activities="$recentActivities"
                    title="Aktivitas Terbaru"
                    :viewAllRoute="route('activities.full')"
                    height="h-80"
                    colSpan="col-span-12 lg:col-span-4" />

                {{-- TABEL PRODUK --}}
                @php
                $productHeaders = [
                ['key' => 'id', 'label' => 'ID'],
                ['key' => 'name', 'label' => 'Nama'],
                ['key' => 'sku', 'label' => 'SKU'],
                ['key' => 'category', 'label' => 'Kategori'],
                ['key' => 'supplier', 'label' => 'Supplier'],
                ['key' => 'purchase_price', 'label' => 'Harga Beli'],
                ['key' => 'selling_price', 'label' => 'Harga Jual'],
                ['key' => 'stock', 'label' => 'Stock'],
                ['key' => 'minimum_stock', 'label' => 'Min'],
                'Aksi',
                ];
                @endphp

                <x-table.data-table
                    tableId="tabel-admin-produk"
                    :headers="$productHeaders"
                    title="Data Produk"
                    viewAllRoute="{{ route('products.full') }}"
                    showViewAll
                    height="h-96"
                    colSpan="col-span-12 lg:col-span-12"
                    sortColumn="{{ $sortColumn }}"
                    sortDirection="{{ $sortDirection }}"
                    :searchable="true"
                    searchPlaceholder="Cari produk..."
                    currentSearch="{{ $search }}">
                    @forelse($products as $product)
                    <x-table.rows.product-row :product="$product" />
                    @empty
                    <tr>
                        <td colspan="{{ count($productHeaders) }}" class="px-4 py-8 text-center text-gray-400">
                            @if($search)
                            Tidak ada hasil untuk "{{ $search }}".
                            @else
                            Tidak ada data produk.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </x-table.data-table>

            </div>

            {{-- DATA UNTUK JS --}}
            <script type="application/json" id="chart-data">
                @json($chartData)
            </script>

        </div>
    </div>
    @endsection

    @section('footer')
    <x-footer-dashboard />
    @endsection

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/dashboard-charts.js') }}"></script>
    @endpush