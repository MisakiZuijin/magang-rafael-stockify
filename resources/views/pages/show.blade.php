{{-- resources/views/pages/show.blade.php --}}
@extends('layouts.app')

@section('navbar')
<x-navbar-dashboard />
@endsection

@section('sidebar')
@if(auth()->user()->role === 'Manager Gudang')
<x-sidebar.manager-sidebar />
@else
<x-sidebar.admin-sidebar />
@endif
@endsection

@section('content')
<x-show.detail
    :title="$title"
    :subtitle="$subtitle ?? null"
    :backRoute="$backRoute ?? null"
    :editRoute="$editRoute ?? null"
    :deleteRoute="$deleteRoute ?? null"
    :fields="$fields" />
@endsection

@section('footer')
<x-footer-dashboard />
@endsection