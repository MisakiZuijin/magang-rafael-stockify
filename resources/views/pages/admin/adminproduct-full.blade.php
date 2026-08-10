{{-- resources/views/pages/admin/adminproduct-full.blade.php --}}
@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.admin-sidebar />
@endsection

@section('content')
<x-admin.full-list
    title="Daftar Lengkap Produk"
    subtitle="Kelola dan lihat detail semua produk"
    :backRoute="route('products.index')"
    :createRoute="route('products.create')"
    createLabel="Tambah Produk"
    :searchRoute="route('products.full')"
    :searchQuery="request('search')"
    :headers="['ID', 'Gambar', 'Nama', 'Kategori', 'Harga Jual', 'Stok', 'Aksi']"
    :items="$products"
    emptyMessage="Belum ada produk.">

    @forelse($products as $product)
    <x-table.rows.admin-product-row :product="$product" />
    @empty
    <tr>
        <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada produk.</td>
    </tr>
    @endforelse

</x-admin.full-list>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection