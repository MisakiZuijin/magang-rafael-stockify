@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar-dashboard />
@endsection

@section('content')
<div class="lg: pb-10 min-h-screen bg-gray-50 relative z-0">
    <div class="p-4 sm:p-6 lg:p-8 max-w-xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">
                {{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori' }}
            </h2>

            <form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" method="POST">
                @csrf
                @if(isset($category))
                @method('PUT')
                @endif

                <div class="space-y-5">
                    {{-- Nama Kategori --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                        <input type="text" name="name"
                            value="{{ old('name', $category->name ?? '') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                            placeholder="Contoh: Elektronik">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input type="text" name="slug"
                            value="{{ old('slug', $category->slug ?? '') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                            placeholder="elektronik">
                        @error('slug')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="mt-8 flex gap-3">
                    <button type="submit" class="bg-emerald-600 text-white px-6 py-2.5 rounded-lg hover:bg-emerald-700 transition font-medium">
                        {{ isset($category) ? 'Simpan Perubahan' : 'Tambah Kategori' }}
                    </button>
                    <a href="{{ route('products.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-200 transition font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection