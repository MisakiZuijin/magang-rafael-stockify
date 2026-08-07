@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar-dashboard />
@endsection

@section('content')

@php
$products = $products ?? collect();
$transactions = $transactions ?? collect();
$recentActivities = $recentActivities ?? collect();

$chartData = [
'productNames' => $products->pluck('name')->values(),
'productStocks' => $products->pluck('stock')->values(),
'stockAman' => $products->where('stock', '>', 20)->count(),
'stockSedang' => $products->whereBetween('stock', [10, 20])->count(),
'stockRendah' => $products->where('stock', '<', 10)->count(),
    ];
    @endphp

    <div class="lg: pb-10 min-h-screen relative z-0">
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="grid grid-cols-12 gap-4 lg:gap-6">

                {{-- Card 1 --}}
                <div class="col-span-12 sm:col-span-6 lg:col-span-3 bg-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Produk</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $products->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="col-span-12 sm:col-span-6 lg:col-span-3 bg-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Stok</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $products->sum('stock') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div class="col-span-12 sm:col-span-6 lg:col-span-3 bg-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Stok Rendah (&lt;10)</p>
                            <p class="text-2xl font-bold text-red-600 mt-1">{{ $products->where('stock', '<', 10)->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Card 4 --}}
                <div class="col-span-12 sm:col-span-6 lg:col-span-3 bg-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Transaksi</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $transactions->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- BAR CHART --}}
                <div class="col-span-12 lg:col-span-8 bg-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold text-gray-800">Grafik Stok Barang</h4>
                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">Top 10 Produk</span>
                    </div>
                    <div class="relative h-80 w-full">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>

                {{-- DOUGHNUT CHART --}}
                <div class="col-span-12 lg:col-span-4 bg-white rounded-lg shadow p-5">
                    <h4 class="font-semibold text-gray-800 mb-4">Distribusi Stok</h4>
                    <div class="relative h-64 w-full flex items-center justify-center">
                        <canvas id="stockDoughnut"></canvas>
                    </div>
                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Stok Aman (&gt;20)</span>
                            <span class="font-semibold text-green-600">{{ $chartData['stockAman'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Stok Sedang (10-20)</span>
                            <span class="font-semibold text-yellow-600">{{ $chartData['stockSedang'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Stok Rendah (&lt;10)</span>
                            <span class="font-semibold text-red-600">{{ $chartData['stockRendah'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- ========================================= --}}
                {{-- TABEL TRANSAKSI (lebar 8 kolom) + SCROLL INTERNAL --}}
                {{-- ========================================= --}}
                <div class="col-span-12 lg:col-span-8 bg-white rounded-lg shadow p-5 flex flex-col">
                    <div class="flex items-center justify-between mb-4 flex-shrink-0">
                        <h4 class="text-lg font-semibold text-gray-800">Data Transaksi</h4>
                        <button class="text-sm text-blue-600 hover:underline">Lihat Semua</button>
                    </div>
                    {{-- Wrapper dengan max-height dan scroll --}}
                    <div class="overflow-x-auto overflow-y-auto rounded-lg border border-gray-200 max-h-80">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-600 font-medium sticky top-0 z-10">
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
                            <tbody class="divide-y divide-gray-100">
                                @forelse($transactions->sortBy('id') as $transaction)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-mono text-gray-500">#{{ $transaction->id }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $transaction->product?->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $transaction->user?->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $transaction->type == 'in' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $transaction->type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ $transaction->quantity }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $transaction->status == 'completed' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $transaction->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ $transaction->note ?? '-' }}</td>
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

                {{-- ========================================= --}}
                {{-- AKTIVITAS TERBARU (lebar 4 kolom) + SCROLL INTERNAL --}}
                {{-- ========================================= --}}
                <div class="col-span-12 lg:col-span-4 bg-white rounded-lg shadow p-5 flex flex-col">
                    <div class="flex items-center justify-between mb-4 flex-shrink-0">
                        <h4 class="font-semibold text-gray-800">Aktivitas Terbaru</h4>
                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">{{ $recentActivities->count() }}</span>
                    </div>

                    <div class="space-y-4 overflow-y-auto max-h-80 pr-1">
                        @forelse($recentActivities as $activity)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5
                {{ $activity->type == 'in' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                @if($activity->type == 'in')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                                @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-800 font-medium truncate">
                                    {{ $activity->user?->name ?? 'User' }}
                                    <span class="text-gray-500 font-normal">
                                        {{ $activity->type == 'in' ? 'menambah' : 'mengurangi' }} stok
                                    </span>
                                    <span class="font-medium text-gray-800">{{ $activity->product?->name ?? 'Produk' }}</span>
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-gray-400">
                                        {{ $activity->created_at?->diffForHumans() ?? '-' }}
                                    </span>
                                    <span class="text-xs px-1.5 py-0.5 rounded
                        {{ $activity->type == 'in' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                        {{ $activity->type }}
                                    </span>
                                    <span class="text-xs font-semibold text-gray-600">
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

                    <a href="#" class="block mt-4 text-center text-sm text-blue-600 hover:text-blue-800 hover:underline flex-shrink-0">
                        Lihat Semua Aktivitas
                    </a>
                </div>

                {{-- ========================================= --}}
                {{-- TABEL PRODUK (full width) + SCROLL INTERNAL --}}
                {{-- ========================================= --}}
                <div class="col-span-12 bg-white rounded-lg shadow p-5 flex flex-col">
                    <div class="flex items-center justify-between mb-4 flex-shrink-0">
                        <h4 class="text-lg font-semibold text-gray-800">Data Produk</h4>
                        <button class="text-sm text-blue-600 hover:underline">Lihat Semua</button>
                    </div>
                    {{-- Wrapper dengan max-height dan scroll --}}
                    <div class="overflow-x-auto overflow-y-auto rounded-lg border border-gray-200 max-h-96">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-600 font-medium sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">Harga Beli</th>
                                    <th class="px-4 py-3">Harga Jual</th>
                                    <th class="px-4 py-3">Stock</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($products->sortBy('id') as $product)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-mono text-gray-500">#{{ $product->id }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                                    <td class="px-4 py-3 text-gray-500">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-gray-500">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $product->stock < 10 ? 'bg-red-100 text-red-700' : ($product->stock < 20 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detail</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">Tidak ada data produk.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ========================================= --}}
                {{-- DATA UNTUK JS --}}
                {{-- ========================================= --}}
                <script type="application/json" id="chart-data">
                    @json($chartData)
                </script>

                @endsection

                @section('footer')
                <x-footer-dashboard />
                @endsection

                @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                @vite(['resources/js/dashboard-charts.js'])
                @endpush