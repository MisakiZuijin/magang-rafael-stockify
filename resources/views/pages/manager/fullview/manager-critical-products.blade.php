{{-- resources/views/pages/manager/manager-critical-products.blade.php --}}
@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.manager-sidebar />
@endsection

@section('content')
<x-admin.full-list
    title="Barang Stok Menipis & Kritis"
    subtitle="Daftar lengkap produk dengan stok di bawah batas minimum"
    :backRoute="route('manager.dashboard')"
    :searchRoute="route('manager.products.critical')"
    :searchQuery="request('search')"
    :headers="['ID', 'Nama Produk', 'Kategori', 'Stok', 'Minimum', 'Status']"
    :items="$products"
    searchPlaceholder="Cari nama, SKU, atau kategori..."
    emptyMessage="Tidak ada barang dengan stok kritis. Stok aman!">

    @forelse($products->sortBy('id') as $product)
    <x-table.rows.critical-stock-row :product="$product" />
    @empty
    <tr>
        <td colspan="6" class="px-4 py-8 text-center text-gray-400">Tidak ada barang dengan stok kritis.</td>
    </tr>
    @endforelse

</x-admin.full-list>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection