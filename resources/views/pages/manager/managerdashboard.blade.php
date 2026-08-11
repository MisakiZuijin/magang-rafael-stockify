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
        <x-page-header title="Dashboard Manager" subtitle="Pantau stok kritis dan aktivitas gudang hari ini" />

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <x-card.cards label="Stok Kritis" :value="$criticalProducts->count()" color="red" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </x-card.cards>

            <x-card.cards label="Masuk Hari Ini" :value="$todayIncoming->sum('quantity')" color="green" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </x-card.cards>

            <x-card.cards label="Keluar Hari Ini" :value="$todayOutgoing->sum('quantity')" color="blue" colSpan="col-span-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
            </x-card.cards>
        </div>

        <div class="grid grid-cols-12 gap-4 lg:gap-6">

            {{-- SECTION 1: STOK KRITIS — Sort + Search --}}
            @php
            $criticalHeaders = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Nama Produk'],
            ['key' => 'category', 'label' => 'Kategori'],
            ['key' => 'stock', 'label' => 'Stok'],
            ['key' => 'minimum_stock', 'label' => 'Minimum'],
            'Status',
            ];
            @endphp

            <x-table.data-table
                tableId="tabel-kritis"
                :headers="$criticalHeaders"
                title="Barang Stok Menipis / Kritis"
                subtitle="Produk dengan stok di bawah atau sama dengan batas minimum"
                viewAllRoute="{{ route('manager.products.critical') }}"
                colSpan="col-span-12"
                height="h-[350px]"
                sortColumn="{{ $sortColumn }}"
                sortDirection="{{ $sortDirection }}"
                :searchable="true"
                searchPlaceholder="Cari nama, SKU, atau kategori..."
                currentSearch="{{ $search }}">

                @forelse($criticalProducts as $product)
                <x-table.rows.critical-stock-row :product="$product" />
                @empty
                <tr>
                    <td colspan="{{ count($criticalHeaders) }}" class="px-4 py-8 text-center text-gray-400">
                        @if($search)
                        Tidak ada hasil untuk "{{ $search }}".
                        @else
                        Tidak ada barang dengan stok kritis. Stok aman!
                        @endif
                    </td>
                </tr>
                @endforelse
            </x-table.data-table>

            {{-- SECTION 2: BARANG MASUK HARI INI — Sort only --}}
            @php
            $incomingHeaders = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'product', 'label' => 'Produk'],
            ['key' => 'user', 'label' => 'User'],
            ['key' => 'quantity', 'label' => 'Qty'],
            ['key' => 'status', 'label' => 'Status'],
            'Waktu',
            ];
            @endphp

            <x-table.data-table
                tableId="tabel-masuk"
                :headers="$incomingHeaders"
                title="Barang Masuk Hari Ini"
                :subtitle="'Total: ' . $todayIncoming->sum('quantity') . ' unit • ' . now()->format('d M Y')"
                colSpan="col-span-12 lg:col-span-6"
                height="h-[350px]"
                sortColumn="{{ $sortColumn }}"
                sortDirection="{{ $sortDirection }}"
                :searchable="false">
                <x-slot name="headerAction">
                    <span class="text-xs bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-3 py-1 rounded-full">
                        {{ $todayIncoming->count() }} Transaksi
                    </span>
                </x-slot>

                @forelse($todayIncoming as $trx)
                <x-table.rows.today-transaction-row :trx="$trx" />
                @empty
                <tr>
                    <td colspan="{{ count($incomingHeaders) }}" class="px-4 py-8 text-center text-gray-400">
                        Belum ada barang masuk hari ini.
                    </td>
                </tr>
                @endforelse
            </x-table.data-table>

            {{-- SECTION 3: BARANG KELUAR HARI INI — Sort only --}}
            @php
            $outgoingHeaders = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'product', 'label' => 'Produk'],
            ['key' => 'user', 'label' => 'User'],
            ['key' => 'quantity', 'label' => 'Qty'],
            ['key' => 'status', 'label' => 'Status'],
            'Waktu',
            ];
            @endphp

            <x-table.data-table
                tableId="tabel-keluar"
                :headers="$outgoingHeaders"
                title="Barang Keluar Hari Ini"
                :subtitle="'Total: ' . $todayOutgoing->sum('quantity') . ' unit • ' . now()->format('d M Y')"
                colSpan="col-span-12 lg:col-span-6"
                height="h-[350px]"
                sortColumn="{{ $sortColumn }}"
                sortDirection="{{ $sortDirection }}"
                :searchable="false">
                <x-slot name="headerAction">
                    <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-full">
                        {{ $todayOutgoing->count() }} Transaksi
                    </span>
                </x-slot>

                @forelse($todayOutgoing as $trx)
                <x-table.rows.today-transaction-row :trx="$trx" />
                @empty
                <tr>
                    <td colspan="{{ count($outgoingHeaders) }}" class="px-4 py-8 text-center text-gray-400">
                        Belum ada barang keluar hari ini.
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