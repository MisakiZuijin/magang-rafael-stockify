@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar-dashboard />
@endsection

@section('content')
<div class="lg: pb-10 min-h-screen relative z-0">
    <div class="p-4 sm:p-6 lg:p-8">

        {{-- Flash Message --}}
        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between">
            <span class="text-sm font-medium">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-100">Daftar Produk</h1>
                <p class="text-sm text-gray-300 mt-1">Kelola semua produk yang tersedia di sistem</p>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4 lg:gap-6">
            {{-- ========================================= --}}
            {{-- TABEL PRODUK (lebar 8 kolom) --}}
            {{-- ========================================= --}}
            <div class="col-span-12 lg:col-span-8 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800">Daftar Produk</h2>
                    <a href="{{ route('products.create') }}" class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </a>
                </div>
                <div class="overflow-x-auto overflow-y-auto max-h-[300px]">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Gambar</th>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3 text-right">Harga Jual</th>
                                <th class="px-4 py-3 text-center">Stok</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($products->sortBy('id') as $product)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-mono text-gray-500">#{{ $product->id }}</td>
                                <td class="px-4 py-3">
                                    @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $product->categori?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $product->stock < 10 ? 'bg-red-100 text-red-700' : ($product->stock < 20 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('products.edit', $product->id) }}" class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada produk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ========================================= --}}
            {{-- TABEL KATEGORI (lebar 4 kolom) --}}
            {{-- ========================================= --}}
            <div class="col-span-12 lg:col-span-4 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800">Kategori</h2>
                    <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-1.5 bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </a>
                </div>
                <div class="overflow-y-auto max-h-[300px]">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3 text-right">Produk</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($categories->sortBy('id') as $category)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $category->name }}</td>
                                <td class="px-4 py-3 text-right text-gray-500">{{ $category->products_count ?? $category->products?->count() ?? 0 }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('categories.edit', $category->id) }}" class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kategori ini? Semua produk dengan kategori ini mungkin terpengaruh.')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400">Belum ada kategori.</td>
                            </tr>
                            @endforelse
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