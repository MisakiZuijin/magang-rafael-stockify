{{-- resources/views/pages/admin/adminactivity-full.blade.php --}}
@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.admin-sidebar />
@endsection

@section('content')
<x-admin.full-list
    title="Semua Aktivitas Pengguna"
    subtitle="Riwayat lengkap aktivitas dan transaksi dalam sistem"
    :backRoute="route('dashboard')"
    :searchRoute="route('activities.full')"
    :searchQuery="request('search')"
    :headers="['Pengguna', 'Aktivitas', 'Tipe', 'Waktu']"
    :items="$activities"
    searchPlaceholder="Cari pengguna atau aktivitas..."
    emptyMessage="Belum ada aktivitas.">

    @forelse($activities as $activity)
    <x-table.rows.activity-row :activity="$activity" />
    @empty
    <tr>
        <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada aktivitas.</td>
    </tr>
    @endforelse

</x-admin.full-list>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection