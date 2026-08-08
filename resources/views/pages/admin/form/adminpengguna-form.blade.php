@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.admin-sidebar />
@endsection

@section('content')
<div class="lg:pb-10 min-h-screen bg-gray-900 relative z-0">
    <div class="p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 dark:bg-gray-800 p-6">

            {{-- Header --}}
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna' }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ isset($user) ? 'Perbarui data pengguna yang sudah terdaftar.' : 'Tambahkan pengguna baru ke dalam sistem.' }}
                </p>
            </div>

            <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}"
                method="POST">
                @csrf
                @if(isset($user))
                @method('PUT')
                @endif

                <div class="space-y-5">

                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                            value="{{ old('name', $user->name ?? '') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                            placeholder="Masukkan nama lengkap">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email"
                            value="{{ old('email', $user->email ?? '') }}"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                            placeholder="nama@email.com">
                        @error('email')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select id="role" name="role"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white bg-white dark:bg-gray-700">
                            <option value="" disabled {{ old('role', $user->role ?? '') == '' ? 'selected' : '' }}>
                                Pilih Role
                            </option>
                            <option value="Manager Gudang" {{ old('role', $user->role ?? '') == 'Manager Gudang' ? 'selected' : '' }}>
                                Manager Gudang
                            </option>
                            <option value="Staff Gudang" {{ old('role', $user->role ?? '') == 'Staff Gudang' ? 'selected' : '' }}>
                                Staff Gudang
                            </option>
                        </select>
                        @error('role')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Password
                            @if(!isset($user))
                            <span class="text-red-500">*</span>
                            @else
                            <span class="text-gray-400 font-normal">(kosongkan jika tidak ingin mengubah)</span>
                            @endif
                        </label>
                        <input type="password" id="password" name="password"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                            placeholder="{{ isset($user) ? '••••••••' : 'Minimal 6 karakter' }}">
                        @error('password')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Konfirmasi Password
                            @if(!isset($user))
                            <span class="text-red-500">*</span>
                            @endif
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ulangi password">
                    </div>

                </div>

                {{-- Tombol Aksi --}}
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <button type="submit"
                        class="w-full sm:w-auto bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition font-medium focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        {{ isset($user) ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
                    </button>
                    <a href="{{ route('users.index') }}"
                        class="w-full sm:w-auto text-center bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-200 transition font-medium dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection