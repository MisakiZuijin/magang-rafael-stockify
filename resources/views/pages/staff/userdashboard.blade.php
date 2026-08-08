@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
<x-sidebar.staff-sidebar />
@endsection

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Staff Gudang</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-400">Selamat datang, {{ auth()->user()->name }}!</p>
</div>
@endsection

@section('footer')
<x-footer-dashboard />
@endsection