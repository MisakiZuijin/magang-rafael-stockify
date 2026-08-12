@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

<!-- @section('sidebar')
<x-sidebar.manager-sidebar />
@endsection -->

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
            $supplierHeaders = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Nama'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'phone', 'label' => 'Telepon'],
            ['key' => 'address', 'label' => 'Alamat'],
            ['key' => 'products_count', 'label' => 'Produk'],
            ];
            @endphp

            <x-table.data-table
                tableId="tabel-supplier"
                :headers="$supplierHeaders"
                title="Daftar Supplier"
                subtitle="Semua supplier yang terdaftar"
                colSpan="col-span-12"
                height="h-[400px]"
                sortColumn="{{ $sortColumn }}"
                sortDirection="{{ $sortDirection }}"
                :searchable="true"
                searchPlaceholder="Cari nama, email, telepon, atau alamat..."
                currentSearch="{{ $search }}">

                @forelse($suppliers as $supplier)
                <x-table.rows.manager-supplier-row :supplier="$supplier" />
                @empty
                <tr>
                    <td colspan="{{ count($supplierHeaders) }}" class="px-4 py-8 text-center text-gray-400">
                        @if($search)
                        Tidak ada hasil untuk "{{ $search }}".
                        @else
                        Belum ada supplier.
                        @endif
                    </td>
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