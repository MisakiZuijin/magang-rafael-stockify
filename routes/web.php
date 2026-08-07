<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserController;

Route::get(
    '/transactions',
    [AdminDashboardController::class, 'index']
)->name('pages.dashboard.admin.admindashboard');

Route::get(
    '/products',
    [AdminDashboardController::class, 'index']
)->name('pages.dashboard.admin.admindashboard');

Route::get(
    '/users',
    [UserController::class, 'index']
)->name('pages.testing');

Route::get(
    '/users/{id}',
    [UserController::class, 'show']
)->name('pages.test');
