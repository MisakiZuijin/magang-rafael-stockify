@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.admin-sidebar />
@endsection

@section('content')
<div class="lg: pb-10 min-h-screen bg-gray-900 relative z-0">
    <div class="p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                {{ isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier' }}
            </h2>

            <form action="{{ isset($supplier) ? route('suppliers.update', $supplier->id) : route('suppliers.store') }}"
                method="POST">
                @csrf
                @if(isset($supplier))
                @method('PUT')
                @endif

                <div class="space-y-5">
                    {{-- Nama Supplier --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Supplier</label>
                        <input type="text" name="name"
                            value="{{ old('name', $supplier->name ?? '') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Contoh: PT. Maju Jaya">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input type="email" name="email"
                            value="{{ old('email', $supplier->email ?? '') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="supplier@email.com">
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input type="text" name="phone"
                            value="{{ old('phone', $supplier->phone ?? '') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="0812-3456-7890">
                        @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea name="address" rows="3"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Jl. Mawar No. 123, Jakarta">{{ old('address', $supplier->address ?? '') }}</textarea>
                        @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="mt-8 flex gap-3">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition font-medium focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700">
                        {{ isset($supplier) ? 'Simpan Perubahan' : 'Tambah Supplier' }}
                    </button>
                    <a href="{{ route('suppliers.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-200 transition font-medium dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection