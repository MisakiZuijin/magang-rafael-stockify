@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.manager-sidebar />
@endsection

@section('content')
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
@endsection

@section('footer')
<x-footer-dashboard />
@endsection