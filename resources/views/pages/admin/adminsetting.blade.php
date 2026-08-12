@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

<!-- @section('sidebar')
<x-sidebar.admin-sidebar />
@endsection -->

@section('content')
<div class="lg:pb-10 min-h-screen dark:bg-gray-900 relative z-0">
    <div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto">

        {{-- Header --}}
        <x-page-header title="Pengaturan Aplikasi" subtitle="Kelola identitas aplikasi dan informasi perusahaan" />

        {{-- Flash Message --}}
        @if(session('success'))
        <x-alert.flash-message type="success" :message="session('success')" />
        @endif

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- IDENTITAS APLIKASI --}}
            <x-form.settings-section title="Identitas Aplikasi">
                <x-form.input-text
                    name="app_name"
                    label="Nama Aplikasi"
                    :value="$settings['app_name'] ?? 'Stockify'"
                    placeholder="Stockify"
                    required />

                <x-form.input-file
                    name="app_logo"
                    label="Logo Aplikasi"
                    accept="image/png,image/jpg,image/jpeg,image/svg+xml"
                    hint="Format: PNG, JPG, SVG. Maks 2MB."
                    :previewUrl="(!empty($settings['app_logo']) && file_exists(public_path($settings['app_logo']))) ? asset($settings['app_logo']) : null"
                    fallbackIcon='<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>' />
            </x-form.settings-section>

            {{-- INFORMASI PERUSAHAAN --}}
            <x-form.settings-section title="Informasi Perusahaan">
                <x-form.input-text
                    name="company_name"
                    label="Nama Perusahaan"
                    :value="$settings['company_name'] ?? ''"
                    placeholder="PT. Contoh Sejahtera" />

                <x-form.input-textarea
                    name="company_address"
                    label="Alamat"
                    :value="$settings['company_address'] ?? ''"
                    placeholder="Jl. Mawar No. 123, Jakarta" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-form.input-text
                        name="company_phone"
                        label="Telepon"
                        :value="$settings['company_phone'] ?? ''"
                        placeholder="021-12345678" />

                    <x-form.input-text
                        name="company_email"
                        label="Email"
                        type="email"
                        :value="$settings['company_email'] ?? ''"
                        placeholder="info@perusahaan.com" />
                </div>
            </x-form.settings-section>

            {{-- Tombol Simpan --}}
            <x-form.form-actions submitLabel="Simpan Pengaturan" cancelLabel="Batal" :cancelUrl="route('dashboard')" />

        </form>
    </div>
</div>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection