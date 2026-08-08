@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.admin-sidebar />
@endsection

@section('content')
<div class="lg: pb-10 min-h-screen relative z-0">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">

        {{-- Header --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Stock</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola semua stock yang tersedia di sistem</p>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            {{ session('success') }}
        </div>
        @endif

        {{-- Alert Low Stock --}}
        @if($lowStockProducts->count() > 0)
        <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">Perhatian!</span> Terdapat {{ $lowStockProducts->count() }} produk dengan stok di bawah minimum.
        </div>
        @endif

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="grid grid-cols-2 items-center">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Transaksi</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $transactions->count() }}</div>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full grid place-items-center text-blue-600 justify-self-end">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="grid grid-cols-2 items-center">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Stock Masuk</div>
                        <div class="text-2xl font-bold text-green-600">{{ $transactions->where('type', 'Masuk')->sum('quantity') }}</div>
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
                        <div class="text-sm text-gray-500 dark:text-gray-400">Stock Keluar</div>
                        <div class="text-2xl font-bold text-red-600">{{ $transactions->where('type', 'Keluar')->sum('quantity') }}</div>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-full grid place-items-center text-red-600 justify-self-end">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="grid grid-cols-2 items-center">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Pending</div>
                        <div class="text-2xl font-bold text-orange-600">{{ $transactions->where('status', 'Pending')->count() }}</div>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full grid place-items-center text-orange-600 justify-self-end">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Transaksi Keluar Masuk --}}
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="grid grid-cols-2 items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Transaksi Keluar Masuk</h2>
                <a href="{{ route('transactions.index') }}" class="text-sm text-blue-600 hover:underline justify-self-end">Lihat Semua</a>
            </div>
            {{-- Wrapper dengan max-height dan scroll --}}
            <div class="overflow-x-auto overflow-y-auto rounded-lg border border-gray-700 max-h-80 scrollbar-hide">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Produk</th>
                            <th class="px-6 py-3">User</th>
                            <th class="px-6 py-3">Tipe</th>
                            <th class="px-6 py-3">Jumlah</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($transactions->sortByDesc('date') as $trx)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-600">
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($trx->date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $trx->product->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">{{ $trx->user->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @php
                                $badgeClass = $trx->type === 'Masuk'
                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                @endphp
                                <span class="{{ $badgeClass }} text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $trx->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $trx->quantity }}</td>
                            <td class="px-6 py-4">
                                @php
                                $statusClass = match($trx->status) {
                                'Diterima' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                'Dikeluarkan' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                'Ditolak' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                };
                                @endphp
                                <span class="{{ $statusClass }} text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $trx->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $trx->note ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center">Belum ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-12 lg:grid-cols-12 gap-6">

            {{-- Stock Opname --}}
            <div class="col-span-5 p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Stock Opname</h2>
                <form action="{{ route('stock.opname') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Produk</label>
                        <select name="product_id" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products->sortBy('id') as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} (Stock: {{ $product->stock }})
                            </option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stock Fisik (Aktual)</label>
                        <input type="number" name="actual_stock" min="0" required
                            class="no-spinner bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Masukkan jumlah stock fisik">
                        @error('actual_stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan (Opsional)</label>
                        <input type="text" name="notes" maxlength="255"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Contoh: Rusak, hilang, dll">
                    </div>

                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">
                        Simpan Opname
                    </button>
                </form>
            </div>

            {{-- Pengaturan Stock Minimum --}}
            <div class="col-span-7 p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pengaturan Stock Minimum</h2>
                <div class="overflow-x-auto overflow-y-auto rounded-lg border border-gray-400 dark:border-gray-700 max-h-96 scrollbar-hide">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3">Stock</th>
                                <th class="px-4 py-3">Minimum</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($products->sortBy('id') as $product)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    {{ $product->name }}
                                    @if($product->minimum_stock > 0 && $product->stock <= $product->minimum_stock)
                                        <span class="ml-2 text-xs text-red-600 font-semibold">KRITIS</span>
                                        @endif
                                </td>
                                <td class="px-4 py-3">{{ $product->stock }}</td>
                                <td class="px-4 py-3">
                                    <form action="{{ route('stock.minimum.update', $product->id) }}" method="POST" class="grid grid-cols-[1fr_auto] items-center gap-2">
                                        @csrf
                                        <input type="number" name="minimum_stock" value="{{ $product->minimum_stock }}" min="0"
                                            class="no-spinner w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-blue-600 dark:hover:bg-blue-700">
                                            Simpan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection