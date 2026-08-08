@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.admin-sidebar />
@endsection

@section('content')

@php
$stockStatusBadge = fn($product) => match(true) {
$product->stock == 0 => ['bg-gray-400 text-gray-900', 'Habis'],
$product->stock <= $product->minimum_stock => ['bg-red-400 text-red-900', 'Kritis'],
    default => ['bg-green-400 text-green-900', 'Aman'],
    };

    $typeBadge = fn($type) => $type == 'Masuk'
    ? ['bg-green-400 text-green-900', 'Masuk']
    : ['bg-red-400 text-red-900', 'Keluar'];

    $statusBadge = fn($status) => match($status) {
    'Diterima' => 'bg-green-400 text-green-900',
    'Pending' => 'bg-yellow-400 text-yellow-900',
    'Dikeluarkan' => 'bg-blue-400 text-blue-900',
    default => 'bg-red-400 text-red-900',
    };

    // ==========================================
    // CHART DATA — untuk dikirim ke JS
    // ==========================================
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
        <div class="p-4 sm:p-6 lg:p-8">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ringkasan stok, transaksi, dan aktivitas pengguna</p>
            </div>

            {{-- FILTER PANEL --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">
                <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">

                    {{-- Dari Tanggal --}}
                    <div class="min-w-0">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $filters['start_date'] }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    {{-- Sampai Tanggal --}}
                    <div class="min-w-0">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $filters['end_date'] }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    {{-- Kategori --}}
                    <div class="min-w-0">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Kategori</label>
                        <select name="category_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none scrollbar-hide">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $filters['category_id'] == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tipe Transaksi --}}
                    <div class="min-w-0">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tipe Transaksi</label>
                        <select name="type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">Semua</option>
                            <option value="Masuk" {{ $filters['type'] == 'Masuk' ? 'selected' : '' }}>Barang Masuk</option>
                            <option value="Keluar" {{ $filters['type'] == 'Keluar' ? 'selected' : '' }}>Barang Keluar</option>
                        </select>
                    </div>

                    {{-- Status Stok --}}
                    <div class="min-w-0">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status Stok</label>
                        <select name="stock_status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">Semua</option>
                            <option value="aman" {{ $filters['stock_status'] == 'aman' ? 'selected' : '' }}>Stok Aman</option>
                            <option value="kritis" {{ $filters['stock_status'] == 'kritis' ? 'selected' : '' }}>Stok Kritis</option>
                            <option value="habis" {{ $filters['stock_status'] == 'habis' ? 'selected' : '' }}>Stok Habis</option>
                        </select>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex gap-2 min-w-0">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-medium px-3 py-2 rounded-lg transition">
                            Terapkan
                        </button>
                        <a href="{{ route('reports.index') }}" class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 text-xs sm:text-sm font-medium px-3 py-2 rounded-lg transition text-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- SUMMARY CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="grid grid-cols-2 items-center">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Produk</p>
                            <p class="text-2xl font-bold text-white mt-1">{{ $stockSummary['total_products'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full grid place-items-center text-blue-600 justify-self-end">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="grid grid-cols-2 items-center">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Stok Aman</p>
                            <p class="text-2xl font-bold text-green-600 mt-1">{{ $stockSummary['stock_aman'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full grid place-items-center text-green-600 justify-self-end">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="grid grid-cols-2 items-center">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Barang Masuk (Qty)</p>
                            <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($transactionSummary['total_masuk'], 0, ',', '.') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full grid place-items-center text-green-600 justify-self-end">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="grid grid-cols-2 items-center">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Barang Keluar (Qty)</p>
                            <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($transactionSummary['total_keluar'], 0, ',', '.') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-full grid place-items-center text-red-600 justify-self-end">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CHARTS ROW --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Stok per Kategori</h4>
                    <div class="relative h-72">
                        <canvas id="stockCategoryChart"></canvas>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">
                        Transaksi Harian ({{ \Carbon\Carbon::parse($filters['start_date'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($filters['end_date'])->format('d M Y') }})
                    </h4>
                    <div class="relative h-72">
                        <canvas id="transactionDailyChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- SECTION 1: LAPORAN STOK BARANG --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-3">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Laporan Stok Barang</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Detail stok berdasarkan filter yang dipilih</p>
                    </div>
                    <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-full">
                        {{ $stockReport->count() }} Produk
                    </span>
                </div>
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 max-h-[400px] scrollbar-hide">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Nama Produk</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Supplier</th>
                                <th class="px-4 py-3 text-right">Harga Beli</th>
                                <th class="px-4 py-3 text-right">Harga Jual</th>
                                <th class="px-4 py-3 text-center">Stok</th>
                                <th class="px-4 py-3 text-center">Min</th>
                                <th class="px-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($stockReport->sortBy('id') as $product)
                            @php [$badgeClass, $badgeLabel] = $stockStatusBadge($product); @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $product->id }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $product->categori?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $product->supplier?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">{{ $product->stock }}</td>
                                <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">{{ $product->minimum_stock }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-400">Tidak ada data stok yang sesuai filter.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SECTION 2: LAPORAN TRANSAKSI --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-3">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Laporan Transaksi Barang Masuk & Keluar</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Periode: {{ \Carbon\Carbon::parse($filters['start_date'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($filters['end_date'])->format('d M Y') }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <span class="text-xs bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-3 py-1 rounded-full">Masuk: {{ $transactionSummary['count_masuk'] }}x</span>
                        <span class="text-xs bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 px-3 py-1 rounded-full">Keluar: {{ $transactionSummary['count_keluar'] }}x</span>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 max-h-[400px] scrollbar-hide">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3">User</th>
                                <th class="px-4 py-3 text-center">Tipe</th>
                                <th class="px-4 py-3 text-center">Qty</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($transactionReport->sortBy('date') as $trx)
                            @php [$typeClass, $typeLabel] = $typeBadge($trx->type); @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $trx->id }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                    {{ $trx->date ? \Carbon\Carbon::parse($trx->date)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $trx->product?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $trx->user?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-center"><span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $typeClass }}">{{ $typeLabel }}</span></td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">{{ $trx->quantity }}</td>
                                <td class="px-4 py-3 text-center"><span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusBadge($trx->status) }}">{{ $trx->status }}</span></td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400 max-w-xs truncate">{{ $trx->note ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-400">Tidak ada data transaksi pada periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SECTION 3: LAPORAN AKTIVITAS PENGGUNA --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-3">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Laporan Aktivitas Pengguna</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Rekapitulasi transaksi yang dilakukan setiap pengguna</p>
                    </div>
                </div>
                <div class="space-y-4">
                    @forelse($userActivityReport as $user)
                    @if($user->stock_transactions_count > 0)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 grid place-items-center text-xs font-bold">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->role }} • {{ $user->stock_transactions_count }} transaksi</p>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto max-h-[300px] scrollbar-hide">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs uppercase sticky top-0">
                                    <tr>
                                        <th class="px-4 py-2">Tanggal</th>
                                        <th class="px-4 py-2">Produk</th>
                                        <th class="px-4 py-2 text-center">Tipe</th>
                                        <th class="px-4 py-2 text-center">Qty</th>
                                        <th class="px-4 py-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($user->stockTransactions as $activity)
                                    @php [$actClass, $actLabel] = $typeBadge($activity->type); @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                        <td class="px-4 py-2 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                            {{ $activity->date ? \Carbon\Carbon::parse($activity->date)->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">{{ $activity->product?->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $actClass }}">{{ $actLabel }}</span></td>
                                        <td class="px-4 py-2 text-center font-semibold text-gray-900 dark:text-white">{{ $activity->quantity }}</td>
                                        <td class="px-4 py-2"><span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusBadge($activity->status) }}">{{ $activity->status }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    @empty
                    <div class="text-center py-8 text-gray-400 text-sm border border-dashed border-gray-300 dark:border-gray-600 rounded-lg">Tidak ada aktivitas pengguna pada periode ini.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- ========================================== --}}
    {{-- DATA UNTUK JS --}}
    {{-- ========================================== --}}
    <script type="application/json" id="chart-data">
        @json($chartData)
    </script>

    @endsection

    @section('footer')
    <x-footer-dashboard />
    @endsection

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const raw = document.getElementById('chart-data');

            if (!raw) {
                console.error('[Laporan Chart] ❌ Element #chart-data tidak ditemukan!');
                return;
            }

            let data;
            try {
                data = JSON.parse(raw.textContent.trim());
            } catch (e) {
                console.error('[Laporan Chart] ❌ Gagal parse JSON:', e);
                return;
            }

            console.log('[Laporan Chart] ✅ Data loaded:', data);

            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)';
            const textColor = isDark ? '#9ca3af' : '#6b7280';

            // ==========================================
            // CHART 1: Stok per Kategori (Grouped Bar)
            // ==========================================
            const stockCanvas = document.getElementById('stockCategoryChart');
            if (stockCanvas && data.stockLabels && data.stockLabels.length > 0) {
                new Chart(stockCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.stockLabels,
                        datasets: [{
                                label: 'Stok Saat Ini',
                                data: data.stockData,
                                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 1,
                                borderRadius: 4,
                            },
                            {
                                label: 'Stok Minimum',
                                data: data.stockMinimum,
                                backgroundColor: 'rgba(239, 68, 68, 0.3)',
                                borderColor: 'rgba(239, 68, 68, 1)',
                                borderWidth: 2,
                                borderRadius: 4,
                                borderDash: [5, 5],
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: gridColor
                                },
                                ticks: {
                                    color: textColor,
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: textColor
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: textColor
                                }
                            }
                        }
                    }
                });
            }

            // ==========================================
            // CHART 2: Transaksi Harian (Line)
            // ==========================================
            const trxCanvas = document.getElementById('transactionDailyChart');
            if (trxCanvas && data.trxLabels && data.trxLabels.length > 0) {
                new Chart(trxCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.trxLabels,
                        datasets: [{
                                label: 'Barang Masuk',
                                data: data.trxMasuk,
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointRadius: 3,
                            },
                            {
                                label: 'Barang Keluar',
                                data: data.trxKeluar,
                                borderColor: 'rgb(239, 68, 68)',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointRadius: 3,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: gridColor
                                },
                                ticks: {
                                    color: textColor,
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: textColor,
                                    maxRotation: 45,
                                    minRotation: 45,
                                    autoSkip: true,
                                    maxTicksLimit: 10
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: textColor
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush