{{-- resources/views/pages/manager/managertransaction-form.blade.php --}}
@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.manager-sidebar />
@endsection

@section('content')
<div class="lg:pb-10 min-h-screen bg-gray-900 relative z-0">
    <div class="p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-6">

            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                Catat Barang {{ $type }}
            </h2>

            <form action="{{ route('manager.transactions.store') }}" method="POST">
                @csrf

                <input type="hidden" name="type" value="{{ $type }}">

                {{-- Produk --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pilih Produk</label>
                    <select name="product_id" required
                        class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (Stok: {{ $product->stock }})
                        </option>
                        @endforeach
                    </select>
                    @error('product_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Tanggal --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}" required
                        class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Jumlah --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah {{ $type }}</label>
                    <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required
                        class="no-spinner w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Catatan --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan (Opsional)</label>
                    <textarea name="note" rows="2"
                        class="w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('note') }}</textarea>
                </div>

                {{-- Tombol --}}
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-{{ $type === 'Masuk' ? 'green' : 'red' }}-600 text-white px-6 py-2.5 rounded-lg hover:bg-{{ $type === 'Masuk' ? 'green' : 'red' }}-700 transition font-medium">
                        Simpan Barang {{ $type }}
                    </button>
                    <a href="{{ route('manager.stock') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-200 transition font-medium dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection