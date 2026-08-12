{{-- resources/views/pages/admin/admintransaction-full.blade.php --}}
@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

<!-- @section('sidebar')
<x-sidebar.admin-sidebar />
@endsection -->

@section('content')
<x-admin.full-list
    title="Daftar Lengkap Transaksi"
    subtitle="Kelola dan lihat riwayat semua transaksi barang masuk & keluar"
    :backRoute="route('stock.index')"
    :createRoute="null"
    :searchRoute="route('transactions.full')"
    :searchQuery="request('search')"
    :headers="['ID', 'Tanggal', 'Produk', 'User', 'Tipe', 'Qty', 'Status', 'Catatan']"
    :items="$transactions"
    searchPlaceholder="Cari produk, user, atau status..."
    emptyMessage="Belum ada transaksi.">

    @forelse($transactions as $trx)
    <x-table.rows.report-transaction-row :trx="$trx" />
    @empty
    <tr>
        <td colspan="8" class="px-4 py-8 text-center text-gray-400">Belum ada transaksi.</td>
    </tr>
    @endforelse

</x-admin.full-list>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection