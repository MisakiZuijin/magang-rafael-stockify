<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoriController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('pages.admin.adminproduct');
});

// Route::get(
//     '/Admin',
//     [AdminDashboardController::class, 'index']
// )->name('pages.admin.admindashboard');



Route::get(
    '/dashboard',
    [AdminDashboardController::class, 'index']
)->name('dashboard');

Route::resource(
    'products',
    ProductController::class
)->names([
    'index'   => 'products.index',
    'create'  => 'products.create',
    'store'   => 'products.store',
    'show'    => 'products.show',
    'edit'    => 'products.edit',
    'update'  => 'products.update',
    'destroy' => 'products.destroy',
]);

Route::resource(
    'categories',
    CategoriController::class
)->only([
    'create',
    'store',
    'edit',
    'update',
    'destroy'
])->names([
    'create'  => 'categories.create',
    'store'   => 'categories.store',
    'edit'    => 'categories.edit',
    'update'  => 'categories.update',
    'destroy' => 'categories.destroy',
]);

Route::get(
    '/transactions',
    [TransactionController::class, 'index']
)->name('transactions.index');



Route::get(
    '/users',
    [UserController::class, 'index']
)->name('pages.testing');

Route::get(
    '/users/{id}',
    [UserController::class, 'show']
)->name('pages.test');
