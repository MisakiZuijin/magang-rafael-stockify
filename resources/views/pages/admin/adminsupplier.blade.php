@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.admin-sidebar />
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
        <div class="grid grid-cols-1 sm:grid-cols-2 sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Supplier</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola semua supplier yang tersedia di sistem</p>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4 lg:gap-6">
            {{-- TABEL SUPPLIER --}}
            <div class="col-span-12 bg-white rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 grid grid-cols-1">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 grid grid-cols-2 items-center">
                    <h2 class="font-semibold text-gray-900 dark:text-white">Daftar Supplier</h2>
                    <a href="{{ route('suppliers.create') }}" class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition justify-self-end">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Supplier
                    </a>
                </div>
                <div class="overflow-x-auto overflow-y-auto max-h-[400px] scrollbar-hide">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Telepon</th>
                                <th class="px-4 py-3">Alamat</th>
                                <th class="px-4 py-3 text-center">Produk</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($suppliers->sortBy('id') as $supplier)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-4 py-3 font-mono text-gray-500 dark:text-gray-400">#{{ $supplier->id }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $supplier->name }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $supplier->email ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $supplier->phone ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ $supplier->address ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                        {{ $supplier->products_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="grid grid-flow-col auto-cols-max items-center justify-center gap-2">
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Yakin hapus supplier ini? Semua produk terkait mungkin terpengaruh.')" class="inline">
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
                                <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada supplier.</td>
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