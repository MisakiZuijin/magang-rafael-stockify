@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.admin-sidebar />
@endsection

@section('content')

@php
$products = $products ?? collect();
$transactions = $transactions ?? collect();
$recentActivities = $recentActivities ?? collect();

// ==========================================
// CHART DATA — Sekarang pakai minimum_stock
// ==========================================
$chartData = [
'productNames' => $products->pluck('name')->values(),
'productStocks' => $products->pluck('stock')->values(),
'productMinimums' => $products->pluck('minimum_stock')->values(), // ← BARU

// Doughnut: berbasis perbandingan dengan minimum_stock masing-masing
'stockAman' => $products->filter(fn($p) => $p->stock > $p->minimum_stock)->count(),
'stockKritis' => $products->filter(fn($p) => $p->stock <= $p->minimum_stock && $p->stock > 0)->count(),
    'stockHabis' => $products->where('stock', 0)->count(),
    ];
    @endphp

    <div class="lg: pb-10 min-h-screen relative z-0">
        <div class="p-4 sm:p-6 lg:p-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 sm:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4 lg:gap-6">

                {{-- Card 1 --}}
                <div class="col-span-12 sm:col-span-6 lg:col-span-3 bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5">
                    <div class="grid grid-cols-2 items-center">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Produk</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $products->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full grid place-items-center text-blue-600 justify-self-end">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="col-span-12 sm:col-span-6 lg:col-span-3 bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5">
                    <div class="grid grid-cols-2 items-center">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Stok</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $products->sum('stock') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full grid place-items-center text-green-600 justify-self-end">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Card 3 — Stok Kritis (≤ minimum_stock) --}}
                <div class="col-span-12 sm:col-span-6 lg:col-span-3 bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5">
                    <div class="grid grid-cols-2 items-center">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Stok Kritis (≤ Min)</p>
                            <p class="text-2xl font-bold text-red-600 mt-1">{{ $chartData['stockKritis'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-full grid place-items-center text-red-600 justify-self-end">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Card 4 --}}
                <div class="col-span-12 sm:col-span-6 lg:col-span-3 bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5">
                    <div class="grid grid-cols-2 items-center">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Transaksi</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $transactions->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-full grid place-items-center text-purple-600 justify-self-end">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- ========================================= --}}
                {{-- BAR CHART — Grouped: Stock vs Minimum Stock --}}
                {{-- ========================================= --}}
                <div class="col-span-12 lg:col-span-8 bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5">
                    <div class="grid grid-cols-2 items-center mb-4">
                        <h4 class="font-semibold text-gray-900 dark:text-white">Grafik Stok vs Minimum</h4>
                        <span class="text-xs text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded justify-self-end">Perbandingan per Produk</span>
                    </div>
                    <div class="relative h-80 w-full">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>

                {{-- ========================================= --}}
                {{-- DOUGHNUT CHART — Berbasis Minimum Stock --}}
                {{-- ========================================= --}}
                <div class="col-span-12 lg:col-span-4 bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Distribusi Stok (vs Minimum)</h4>
                    <div class="relative h-64 w-full grid place-items-center">
                        <canvas id="stockDoughnut"></canvas>
                    </div>
                    <div class="mt-4 space-y-2 text-sm">
                        <div class="grid grid-cols-2 items-center">
                            <span class="text-gray-500 dark:text-gray-400">Stok Aman (> min)</span>
                            <span class="font-semibold text-green-600 justify-self-end">{{ $chartData['stockAman'] }}</span>
                        </div>
                        <div class="grid grid-cols-2 items-center">
                            <span class="text-gray-500 dark:text-gray-400">Stok Kritis (≤ min)</span>
                            <span class="font-semibold text-red-600 justify-self-end">{{ $chartData['stockKritis'] }}</span>
                        </div>
                        <div class="grid grid-cols-2 items-center">
                            <span class="text-gray-500 dark:text-gray-400">Stok Habis (0)</span>
                            <span class="font-semibold text-gray-600 justify-self-end">{{ $chartData['stockHabis'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- TABEL TRANSAKSI --}}
                <div class="col-span-12 lg:col-span-8 bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5 grid grid-cols-1">
                    <div class="grid grid-cols-2 items-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Data Transaksi</h4>
                        <button class="text-sm text-blue-600 hover:underline justify-self-end">Lihat Semua</button>
                    </div>
                    <div class="overflow-x-auto overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700 max-h-80 scrollbar-hide">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Nama Product</th>
                                    <th class="px-4 py-3">Nama User</th>
                                    <th class="px-4 py-3">Type</th>
                                    <th class="px-4 py-3">Quantity</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Note</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($transactions->sortBy('id') as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $transaction->id }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $transaction->product?->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $transaction->user?->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $transaction->type == 'Masuk' ? 'bg-green-400 text-green-900' : 'bg-red-400 text-red-900' }}">
                                            {{ $transaction->type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $transaction->quantity }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            {{ match($transaction->status) {
                                                'Diterima' => 'bg-green-400 text-green-900',
                                                'Pending' => 'bg-yellow-400 text-yellow-900',
                                                'Dikeluarkan' => 'bg-blue-400 text-blue-900',
                                                default => 'bg-red-400 text-red-900'
                                            } }}">
                                            {{ $transaction->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $transaction->note ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detail</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">Tidak ada data transaksi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- AKTIVITAS TERBARU --}}
                <div class="col-span-12 lg:col-span-4 bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5 grid grid-cols-1">
                    <div class="grid grid-cols-2 items-center mb-4">
                        <h4 class="font-semibold text-gray-900 dark:text-white">Aktivitas Terbaru</h4>
                        <span class="text-xs text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded justify-self-end">{{ $recentActivities->count() }}</span>
                    </div>

                    <div class="space-y-4 overflow-y-auto max-h-80 pr-1 scrollbar-hide">
                        @forelse($recentActivities as $activity)
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

                {{-- TABEL PRODUK --}}
                <div class="col-span-12 bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-5 grid grid-cols-1">
                    <div class="grid grid-cols-2 items-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Data Produk</h4>
                        <button class="text-sm text-blue-600 hover:underline justify-self-end">Lihat Semua</button>
                    </div>
                    <div class="overflow-x-auto overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700 max-h-96 scrollbar-hide">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">Harga Beli</th>
                                    <th class="px-4 py-3">Harga Jual</th>
                                    <th class="px-4 py-3">Stock</th>
                                    <th class="px-4 py-3">Min</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($products->sortBy('id') as $product)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $product->id }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            {{ $product->stock == 0 ? 'bg-gray-400 text-gray-900' :
                                               ($product->stock <= $product->minimum_stock ? 'bg-red-400 text-red-900' :
                                               ($product->stock <= $product->minimum_stock + 10 ? 'bg-yellow-400 text-yellow-900' : 'bg-green-400 text-green-900')) }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $product->minimum_stock }}</td>
                                    <td class="px-4 py-3">
                                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detail</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">Tidak ada data produk.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const data = JSON.parse(document.getElementById('chart-data').textContent);

                        // ==========================================
                        // BAR CHART — Grouped: Stock vs Minimum
                        // ==========================================
                        const ctxBar = document.getElementById('stockChart').getContext('2d');
                        new Chart(ctxBar, {
                            type: 'bar',
                            data: {
                                labels: data.productNames,
                                datasets: [{
                                        label: 'Stock Saat Ini',
                                        data: data.productStocks,
                                        backgroundColor: data.productStocks.map((stock, i) =>
                                            stock <= data.productMinimums[i] ? 'rgba(239, 68, 68, 0.8)' : 'rgba(59, 130, 246, 0.8)'
                                        ),
                                        borderColor: data.productStocks.map((stock, i) =>
                                            stock <= data.productMinimums[i] ? 'rgb(239, 68, 68)' : 'rgb(59, 130, 246)'
                                        ),
                                        borderWidth: 1,
                                        borderRadius: 4,
                                    },
                                    {
                                        label: 'Stock Minimum',
                                        data: data.productMinimums,
                                        backgroundColor: 'rgba(156, 163, 175, 0.3)',
                                        borderColor: 'rgba(156, 163, 175, 1)',
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
                                        ticks: {
                                            precision: 0
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        position: 'top'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            afterLabel: function(context) {
                                                if (context.datasetIndex === 0) {
                                                    const min = data.productMinimums[context.dataIndex];
                                                    const stock = context.raw;
                                                    if (stock <= min) return '⚠️ Di bawah minimum!';
                                                    return '✅ Aman';
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        });

                        // ==========================================
                        // DOUGHNUT CHART — Berbasis Minimum Stock
                        // ==========================================
                        const ctxDoughnut = document.getElementById('stockDoughnut').getContext('2d');
                        new Chart(ctxDoughnut, {
                            type: 'doughnut',
                            data: {
                                labels: ['Stok Aman', 'Stok Kritis', 'Stok Habis'],
                                datasets: [{
                                    data: [data.stockAman, data.stockKritis, data.stockHabis],
                                    backgroundColor: [
                                        'rgb(34, 197, 94)', // green
                                        'rgb(239, 68, 68)', // red
                                        'rgb(156, 163, 175)' // gray
                                    ],
                                    borderWidth: 0,
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '70%',
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });
                    });
                </script>
                @endpush