@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

<!-- @section('sidebar')
<x-sidebar.admin-sidebar />
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
        <x-page-header title="Daftar Pengguna" subtitle="Kelola semua pengguna yang memiliki akses ke sistem" />

        <div class="grid grid-cols-12 gap-4 lg:gap-6">

            {{-- TABEL PENGGUNA --}}

            @php
            $userHeaders = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Nama'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'role', 'label' => 'Role'],
            'Aksi',
            ];
            @endphp

            <x-table.data-table
                tableId="tabel-admin-pengguna"
                :headers="$userHeaders"
                title="Daftar User"
                subtitle="Semua user yang terdaftar"
                colSpan="col-span-12"
                height="h-[400px]"
                sortColumn="{{ $sortColumn }}"
                sortDirection="{{ $sortDirection }}"
                :searchable="true"
                searchPlaceholder="Cari nama, atau email..."
                currentSearch="{{ $search }}">
                <x-slot:headerAction>
                    <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-full">
                        {{ $users->count() }} user
                    </span>
                    <a href="{{ route('users.create') }}" class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Users
                    </a>
                </x-slot:headerAction>

                @forelse($users as $user)
                <x-table.rows.user-row :user="$user" />
                @empty
                <tr>
                    <td colspan="{{ count($userHeaders) }}" class="px-4 py-8 text-center text-gray-400">
                        @if($search)
                        Tidak ada hasil untuk "{{ $search }}".
                        @else
                        Belum ada user.
                        @endif
                    </td>
                </tr>
                @endforelse
            </x-table.data-table>

            <!-- @php
            $userHeaders = ['ID', 'Nama', 'Email', 'Role', 'Aksi'];
            @endphp

            <x-table.data-table
                :headers="$userHeaders"
                title="Daftar Pengguna"
                maxHeight="max-h-[400px]"
                colSpan="col-span-12">
                <x-slot:headerAction>
                    <a href="{{ route('users.create') }}" class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Pengguna
                    </a>
                </x-slot:headerAction>

                @forelse($users->sortBy('id') as $user)
                <x-table.rows.user-row :user="$user" />
                @empty
                <tr>
                    <td colspan="{{ count($userHeaders) }}" class="px-4 py-8 text-center text-gray-400">Belum ada pengguna.</td>
                </tr>
                @endforelse
            </x-table.data-table> -->

        </div>

    </div>
</div>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection