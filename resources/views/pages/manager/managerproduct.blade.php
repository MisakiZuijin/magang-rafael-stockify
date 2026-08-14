@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

<!-- @section('sidebar')
<x-sidebar.manager-sidebar />
@endsection -->

@section('content')
<div class="lg:pb-10 min-h-screen relative z-0">
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-12 gap-4 lg:gap-6">

            @php
            $productHeaders = [
            ['key' => 'id', 'label' => 'ID'],
            'Gambar',
            ['key' => 'name', 'label' => 'Nama'],
            ['key' => 'category', 'label' => 'Kategori'],
            ['key' => 'supplier', 'label' => 'Supplier'],
            ['key' => 'purchase_price', 'label' => 'Harga Beli'],
            ['key' => 'selling_price', 'label' => 'Harga Jual'],
            ['key' => 'stock', 'label' => 'Stok'],
            'Deskripsi',
            'Aksi',
            ];
            @endphp

            <x-table.data-table
                tableId="tabel-produk-manager"
                :headers="$productHeaders"
                title="Daftar Produk"
                subtitle="Kelola produk yang tersedia di gudang"
                colSpan="col-span-12"
                height="h-[400px]"
                sortColumn="{{ $sortColumn }}"
                sortDirection="{{ $sortDirection }}"
                :searchable="true"
                searchPlaceholder="Cari nama, SKU, atau kategori..."
                currentSearch="{{ $search }}">

                <x-slot:headerAction>
                    <div class="flex items-center gap-2 flex-wrap justify-end">
                        <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-full">
                            {{ $products->count() }} Produk
                        </span>
                        <a href="{{ route('manager.products.create') }}" class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Produk
                        </a>
                    </div>
                </x-slot:headerAction>

                @forelse($products as $product)
                <x-table.rows.manager-product-row :product="$product" />
                @empty
                <tr>
                    <td colspan="{{ count($productHeaders) }}" class="px-4 py-8 text-center text-gray-400">
                        @if($search)
                        Tidak ada produk yang cocok dengan pencarian "{{ $search }}".
                        @else
                        Belum ada produk.
                        @endif
                    </td>
                </tr>
                @endforelse
            </x-table.data-table>

        </div>
    </div>
</div>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection