@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.admin-sidebar />
@endsection

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
            $productHeaders = ['ID', 'Gambar', 'Nama', 'Kategori', 'Harga Jual', 'Stok', 'Aksi'];
            @endphp

            <x-table.data-table
                :headers="$productHeaders"
                title="Daftar Produk"
                maxHeight="max-h-[300px]"
                colSpan="col-span-12">

                <x-slot:headerAction>
                    <div class="flex items-center gap-2 flex-wrap justify-end">
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

                @forelse($products->sortBy('id') as $product)
                <x-table.rows.admin-product-row :product="$product" />
                @empty
                <tr>
                    <td colspan="{{ count($productHeaders) }}" class="px-4 py-8 text-center text-gray-400">Belum ada produk.</td>
                </tr>
                @endforelse
            </x-table.data-table>

            {{-- TABEL KATEGORI --}}
            @php
            $categoryHeaders = ['Nama', 'Produk', 'Aksi'];
            @endphp

            <x-table.data-table
                :headers="$categoryHeaders"
                title="Kategori"
                maxHeight="max-h-[300px]"
                colSpan="col-span-12 lg:col-span-5">
                <x-slot:headerAction>
                    <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Kategori
                    </a>
                </x-slot:headerAction>

                @forelse($categories->sortBy('id') as $category)
                <x-table.rows.category-row :category="$category" />
                @empty
                <tr>
                    <td colspan="{{ count($categoryHeaders) }}" class="px-4 py-8 text-center text-gray-400">Belum ada kategori.</td>
                </tr>
                @endforelse
            </x-table.data-table>

            {{-- TABEL PRODUCT ATTRIBUTES --}}
            @php
            $attributeHeaders = ['ID', 'Nama Produk', 'Nama Atribut', 'Nilai', 'Aksi'];
            @endphp

            <x-table.data-table
                :headers="$attributeHeaders"
                title="Atribut Produk"
                maxHeight="max-h-[300px]"
                colSpan="col-span-12 lg:col-span-7">
                <x-slot:headerAction>
                    <a href="{{ route('product-attributs.create') }}" class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Atribut
                    </a>
                </x-slot:headerAction>

                @forelse($productAttributs->sortBy('id') as $attribut)
                <x-table.rows.product-attribute-row :attribut="$attribut" />
                @empty
                <tr>
                    <td colspan="{{ count($attributeHeaders) }}" class="px-4 py-8 text-center text-gray-400">Belum ada atribut produk.</td>
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