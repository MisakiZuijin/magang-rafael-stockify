@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.manager-sidebar />
@endsection

@section('content')
<div class="lg:pb-10 min-h-screen relative z-0">
    <div class="p-4 sm:p-6 lg:p-8">

        {{-- Flash Message --}}
        @if(session('success'))
        <x-alert.flash-message type="success" :message="session('success')" />
        @endif

        @if(session('error'))
        <x-alert.flash-message type="error" :message="session('error')" />
        @endif

        {{-- Header --}}
        <x-page-header title="Daftar Supplier" subtitle="Kelola semua supplier yang tersedia di sistem" />

        <div class="grid grid-cols-12 gap-4 lg:gap-6">

            {{-- TABEL SUPPLIER --}}
            @php
            $supplierHeaders = ['ID', 'Nama', 'Email', 'Telepon', 'Alamat', 'Produk'];
            @endphp

            <x-table.data-table
                :headers="$supplierHeaders"
                title="Daftar Supplier"
                maxHeight="max-h-[400px]"
                colSpan="col-span-12">

                @forelse($suppliers->sortBy('id') as $supplier)
                <x-table.rows.manager-supplier-row :supplier="$supplier" />
                @empty
                <tr>
                    <td colspan="{{ count($supplierHeaders) }}" class="px-4 py-8 text-center text-gray-400">Belum ada supplier.</td>
                </tr>
                @endforelse
            </x-table.data-table>

        </div>

    </div>
</div>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection