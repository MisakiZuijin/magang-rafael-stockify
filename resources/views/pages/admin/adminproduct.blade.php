@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

<!-- @section('sidebar')
<x-sidebar.admin-sidebar />
@endsection -->

@section('content')
<div class="lg:pb-10 min-h-screen relative z-0">
    <div class="p-4 sm:p-6 lg:p-8">

        {{-- Flash Message --}}
        @if(session('success'))
        <x-alert.flash-message type="success" :message="session('success')" />
        @endif
        @if(session('error'))
        <x-alert.flash-message type="error" :message="session('error')" />
        @endif

        {{-- Header --}}
        <x-page-header title="Daftar Produk" subtitle="Kelola semua produk yang tersedia di sistem" />

        <div class="grid grid-cols-12 gap-4 lg:gap-6">

            {{-- TABEL PRODUK --}}
            @php
            $productHeaders = [
            ['key' => 'id', 'label' => 'ID'],
            'Gambar',
            ['key' => 'name', 'label' => 'Nama'],
            ['key' => 'sku', 'label' => 'SKU'],
            ['key' => 'supplier', 'label' => 'Supplier'],
            ['key' => 'category', 'label' => 'Kategori'],
            ['key' => 'purchase_price', 'label' => 'Harga Beli'],
            ['key' => 'selling_price', 'label' => 'Harga Jual'],
            ['key' => 'stock', 'label' => 'Stok'],
            'Aksi',
            ];
            @endphp

            <x-table.data-table
                tableId="tabel-admin-produk"
                :headers="$productHeaders"
                title="Daftar Produk"
                subtitle="Semua produk yang terdaftar"
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

                        {{-- Import CSV --}}
                        <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" id="importCsvForm" class="flex items-center">
                            @csrf
                            <input type="file" name="csv_file" id="csvInput" accept=".csv" class="hidden" onchange="document.getElementById('importCsvForm').submit()">
                            <button type="button" onclick="document.getElementById('csvInput').click()" class="inline-flex items-center gap-1.5 bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Import CSV
                            </button>
                        </form>

                        {{-- Export CSV --}}
                        <a href="{{ route('products.export') }}" class="inline-flex items-center gap-1.5 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export CSV
                        </a>

                        {{-- Tambah Produk --}}
                        <a href="{{ route('products.create') }}" class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Produk
                        </a>
                    </div>
                </x-slot:headerAction>

                @forelse($products as $product)
                <x-table.rows.admin-product-row :product="$product" />
                @empty
                <tr>
                    <td colspan="{{ count($productHeaders) }}" class="px-4 py-8 text-center text-gray-400">
                        @if($search)
                        Tidak ada hasil untuk "{{ $search }}".
                        @else
                        Belum ada produk.
                        @endif
                    </td>
                </tr>
                @endforelse
            </x-table.data-table>

            {{-- TABEL KATEGORI --}}
            @php
            $categoryHeaders = [
            ['key' => 'name', 'label' => 'Nama'],
            ['key' => 'products_count', 'label' => 'Produk'],
            'Aksi',
            ];
            @endphp

            <x-table.data-table
                tableId="tabel-admin-kategori"
                :headers="$categoryHeaders"
                title="Kategori"
                subtitle="Semua kategori produk"
                colSpan="col-span-12 lg:col-span-6"
                height="h-[400px]"
                sortColumn="{{ $sortColumn }}"
                sortDirection="{{ $sortDirection }}"
                :searchable="true"
                searchPlaceholder="Cari kategori..."
                currentSearch="{{ $search }}">
                <x-slot:headerAction>
                    <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-full">
                        {{ $categories->count() }} Kategori
                    </span>
                    <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Kategori
                    </a>
                </x-slot:headerAction>

                @forelse($categories as $category)
                <x-table.rows.category-row :category="$category" />
                @empty
                <tr>
                    <td colspan="{{ count($categoryHeaders) }}" class="px-4 py-8 text-center text-gray-400">
                        @if($search)
                        Tidak ada hasil untuk "{{ $search }}".
                        @else
                        Belum ada kategori.
                        @endif
                    </td>
                </tr>
                @endforelse
            </x-table.data-table>

            {{-- TABEL PRODUCT ATTRIBUTES --}}
            @php
            $attributeHeaders = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'product', 'label' => 'Nama Produk'],
            ['key' => 'name', 'label' => 'Nama Atribut'],
            ['key' => 'value', 'label' => 'Nilai'],
            'Aksi',
            ];
            @endphp

            <x-table.data-table
                tableId="tabel-admin-atribut"
                :headers="$attributeHeaders"
                title="Atribut Produk"
                subtitle="Atribut tambahan untuk setiap produk"
                colSpan="col-span-12 lg:col-span-6"
                height="h-[400px]"
                sortColumn="{{ $sortColumn }}"
                sortDirection="{{ $sortDirection }}"
                :searchable="true"
                searchPlaceholder="Cari produk atau atribut..."
                currentSearch="{{ $search }}">
                <x-slot:headerAction>
                    <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-full">
                        {{ $productAttributs->count() }} Atribut
                    </span>
                    <a href="{{ route('product-attributs.create') }}" class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Atribut
                    </a>
                </x-slot:headerAction>

                @forelse($productAttributs as $attribut)
                <x-table.rows.product-attribute-row :attribut="$attribut" />
                @empty
                <tr>
                    <td colspan="{{ count($attributeHeaders) }}" class="px-4 py-8 text-center text-gray-400">
                        @if($search)
                        Tidak ada hasil untuk "{{ $search }}".
                        @else
                        Belum ada atribut produk.
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