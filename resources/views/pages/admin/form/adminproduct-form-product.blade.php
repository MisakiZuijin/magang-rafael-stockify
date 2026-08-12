@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

<!-- @section('sidebar')
<x-sidebar.admin-sidebar />
@endsection -->

@section('content')
<div class="lg: pb-10 min-h-screen dark:bg-gray-900 relative z-0">
    <div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Produk</label>
                        <input type="text" name="name"
                            value="{{ old('name', $product->name ?? '') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SKU --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SKU</label>
                        <input type="text" name="sku"
                            value="{{ old('sku', $product->sku ?? '') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('sku')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                        <select name="category_id" class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white transition dark:bg-gray-700 dark:border-gray-600 dark:text-white scrollbar-hide">
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Supplier</label>
                        <select name="supplier_id" class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white transition dark:bg-gray-700 dark:border-gray-600 dark:text-white scrollbar-hide">
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga Beli</label>
                        <input type="text"
                            id="display_purchase_price"
                            value="{{ old('purchase_price', $product->purchase_price ?? '') ? 'Rp ' . number_format(old('purchase_price', $product->purchase_price ?? ''), 0, ',', '.') : '' }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Rp 0"
                            autocomplete="off">
                        <input type="hidden" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', $product->purchase_price ?? '') }}">
                        @error('purchase_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Harga Jual --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga Jual</label>
                        <input type="text"
                            id="display_selling_price"
                            value="{{ old('selling_price', $product->selling_price ?? '') ? 'Rp ' . number_format(old('selling_price', $product->selling_price ?? ''), 0, ',', '.') : '' }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Rp 0"
                            autocomplete="off">
                        <input type="hidden" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price ?? '') }}">
                        @error('selling_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Stok --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stok</label>
                        <input type="number" name="stock"
                            value="{{ old('stock', $product->stock ?? 0) }}"
                            class="no-spinner w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Minimum Stock --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock Minimum (Alert)</label>
                        <input type="number" name="minimum_stock"
                            value="{{ old('minimum_stock', $product->minimum_stock ?? 0) }}"
                            class="no-spinner w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="0">
                        @error('minimum_stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gambar --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar Produk</label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700 dark:file:bg-blue-500 dark:hover:file:bg-blue-600">
                        @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @if(isset($product) && $product->image)
                        <img src="{{ asset('images/' . $product->image) }}" class="mt-2 h-20 rounded border border-gray-200 dark:border-gray-600" alt="Current">
                        @endif
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>

                    {{-- Tombol --}}
                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700">
                            {{ isset($product) ? 'Simpan Perubahan' : 'Tambah Produk' }}
                        </button>
                        <a href="{{ route('products.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition font-medium dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            Batal
                        </a>
                    </div>
            </form>
            @push('scripts')
            <script>
                function formatRupiah(angka, prefix = 'Rp ') {
                    let number_string = angka.replace(/[^,\d]/g, '').toString(),
                        split = number_string.split(','),
                        sisa = split[0].length % 3,
                        rupiah = split[0].substr(0, sisa),
                        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                    return prefix + rupiah;
                }

                function setupRupiahInput(displayId, hiddenId) {
                    const displayInput = document.getElementById(displayId);
                    const hiddenInput = document.getElementById(hiddenId);

                    if (!displayInput || !hiddenInput) return;

                    displayInput.addEventListener('input', function(e) {
                        // Hanya ambil digit
                        let value = this.value.replace(/[^0-9]/g, '');

                        // Update hidden input dengan angka murni
                        hiddenInput.value = value;

                        // Update tampilan dengan format Rupiah
                        this.value = value ? formatRupiah(value) : '';
                    });

                    // Saat blur, pastikan format tetap rapi
                    displayInput.addEventListener('blur', function() {
                        let value = this.value.replace(/[^0-9]/g, '');
                        this.value = value ? formatRupiah(value) : '';
                    });

                    // Saat focus, hilangkan format biar gampang edit
                    displayInput.addEventListener('focus', function() {
                        let value = this.value.replace(/[^0-9]/g, '');
                        this.value = value;
                    });
                }

                // Inisialisasi
                setupRupiahInput('display_purchase_price', 'purchase_price');
                setupRupiahInput('display_selling_price', 'selling_price');
            </script>
            @endpush
        </div>
    </div>
</div>
@endsection