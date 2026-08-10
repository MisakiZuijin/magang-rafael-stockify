@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.admin-sidebar />
@endsection

@section('content')
<div class="lg:pb-10 min-h-screen bg-gray-900 relative z-0">
    <div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan Aplikasi</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola identitas aplikasi dan informasi perusahaan</p>
        </div>

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

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- IDENTITAS APLIKASI --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Identitas Aplikasi</h2>
                <div class="space-y-5">

                    {{-- Nama Aplikasi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Aplikasi <span class="text-red-500">*</span></label>
                        <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? 'Stockify') }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="Stockify">
                        @error('app_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Logo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo Aplikasi</label>
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 grid place-items-center overflow-hidden">
                                @if(!empty($settings['app_logo']) && file_exists(public_path($settings['app_logo'])))
                                <img src="{{ asset($settings['app_logo']) }}?t={{ time() }}" alt="Logo" class="w-full h-full object-contain p-1">
                                @else
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="app_logo" accept="image/png,image/jpg,image/jpeg,image/svg+xml"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                                <p class="text-xs text-gray-400 mt-1">Format: PNG, JPG, SVG. Maks 2MB.</p>
                                @error('app_logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Favicon --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Favicon</label>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 grid place-items-center overflow-hidden">
                                @if(!empty($settings['favicon']) && file_exists(public_path($settings['favicon'])))
                                <img src="{{ asset($settings['favicon']) }}?t={{ time() }}" alt="Favicon" class="w-full h-full object-contain p-1">
                                @else
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="favicon" accept="image/png,image/x-icon"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                                <p class="text-xs text-gray-400 mt-1">Format: PNG, ICO. Maks 1MB.</p>
                                @error('favicon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- INFORMASI PERUSAHAAN --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Perusahaan</h2>
                <div class="space-y-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Perusahaan</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name'] ?? '') }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="PT. Contoh Sejahtera">
                        @error('company_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                        <textarea name="company_address" rows="3"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="Jl. Mawar No. 123, Jakarta">{{ old('company_address', $settings['company_address'] ?? '') }}</textarea>
                        @error('company_address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
                            <input type="text" name="company_phone" value="{{ old('company_phone', $settings['company_phone'] ?? '') }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition"
                                placeholder="021-12345678">
                            @error('company_phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="company_email" value="{{ old('company_email', $settings['company_email'] ?? '') }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition"
                                placeholder="info@perusahaan.com">
                            @error('company_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition font-medium focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Simpan Pengaturan
                </button>
                <a href="{{ route('dashboard') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-200 transition font-medium dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection