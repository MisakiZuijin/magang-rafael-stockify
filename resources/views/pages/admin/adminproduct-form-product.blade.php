@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar-dashboard />
@endsection

@section('content')
<div class="lg: pb-10 min-h-screen bg-gray-50 relative z-0">
    <div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">
                {{ isset($product) ? 'Edit Produk' : 'Tambah Produk' }}
            </h2>

            <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf
                @if(isset($product))
                @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                        <input type="text" name="name"
                            value="{{ old('name', $product->name ?? '') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SKU --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input type="text" name="sku"
                            value="{{ old('sku', $product->sku ?? '') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                        @error('sku')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Supplier --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <select name="supplier_id" class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Harga Beli --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                        <input type="number" name="purchase_price"
                            value="{{ old('purchase_price', $product->purchase_price ?? '') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                        @error('purchase_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Harga Jual --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual</label>
                        <input type="number" name="selling_price"
                            value="{{ old('selling_price', $product->selling_price ?? '') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                        @error('selling_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Stok --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number" name="stock"
                            value="{{ old('stock', $product->stock ?? 0) }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                        @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gambar --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @if(isset($product) && $product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="mt-2 h-20 rounded" alt="Current">
                        @endif
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description', $product->description ?? '') }}</textarea>
                </div>

                {{-- Tombol --}}
                <div class="mt-6 flex gap-3">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        {{ isset($product) ? 'Simpan Perubahan' : 'Tambah Produk' }}
                    </button>
                    <a href="{{ route('products.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection