<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductAttributController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoriController;
use App\Http\Controllers\UserController;

// Route::get('/stock', function () {
//     return view('pages.admin.adminstock');
// });

// ==========================================
// 1. ROOT → Redirect ke login
// ==========================================
Route::get(
    '/',
    function () {
        return redirect()->route('login');
    }
);

// ==========================================
// 2. AUTH (Guest Only)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get(
        '/login',
        [AuthController::class, 'showLogin']
    )->name('login');
    Route::post(
        '/login',
        [AuthController::class, 'login']
    );
});

// ==========================================
// 3. LOGOUT
// ==========================================
Route::post(
    '/logout',
    [AuthController::class, 'logout']
)->name('logout')->middleware('auth');

// ==========================================
// 4. ADMIN
// ==========================================
Route::middleware(['auth', 'role:Admin'])->group(function () {

    Route::get('/activities/full', [AdminDashboardController::class, 'fullActivities'])->name('activities.full');

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // === PRODUK (Full List dulu, baru Resource) ===
    Route::get('/products/full', [ProductController::class, 'full'])->name('products.full');
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    Route::resource('products', ProductController::class);

    // === SUPPLIER (Full List) ===
    Route::get('/suppliers/full', [SupplierController::class, 'full'])->name('suppliers.full');
    Route::resource('suppliers', SupplierController::class);

    // === KATEGORI (Full List) ===
    Route::get('/categories/full', [CategoriController::class, 'full'])->name('categories.full');
    Route::resource('categories', CategoriController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

    // === ATRIBUT PRODUK (Full List) ===
    Route::get('/product-attributs/full', [ProductAttributController::class, 'full'])->name('product-attributs.full');
    Route::resource('product-attributs', ProductAttributController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

    // === PENGGUNA / USERS (Full List) ===
    Route::get('/pengguna/full', [UserController::class, 'full'])->name('users.full');
    Route::resource('pengguna', UserController::class)->names([
        'index'   => 'users.index',
        'create'  => 'users.create',
        'store'   => 'users.store',
        'edit'    => 'users.edit',
        'update'  => 'users.update',
        'destroy' => 'users.destroy',
    ]);

    // === TRANSAKSI (Full List) ===
    Route::get('/transactions/full', [TransactionController::class, 'full'])->name('transactions.full');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
        ->name('transactions.show')
        ->middleware(['auth', 'role:Admin']);

    // Stock, Laporan, Settings tetap ...
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stock/opname', [StockController::class, 'opname'])->name('stock.opname');
    Route::post('/stock/minimum/{product}', [StockController::class, 'updateMinimum'])->name('stock.minimum.update');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/users')->name('pages.testing');
    // Route::get('/users/{id}', [UserController::class, 'show'])->name('pages.test');
});

// ==========================================
// 5. MANAGER GUDANG
// ==========================================
Route::middleware(['auth', 'role:Manager Gudang'])->group(function () {
    Route::get(
        '/manager/dashboard',
        [ManagerDashboardController::class, 'index']
    )->name('manager.dashboard');

    Route::get(
        '/manager/products/critical',
        [ManagerDashboardController::class, 'criticalProducts']
    )->name('manager.products.critical');

    // ← TAMBAHKAN INI: Manager Products
    Route::get('/manager/products', [ProductController::class, 'managerIndex'])
        ->name('manager.products');
    Route::get('/manager/products/create', [ProductController::class, 'create'])
        ->name('manager.products.create');
    Route::post('/manager/products', [ProductController::class, 'store'])
        ->name('manager.products.store');
    Route::get('/manager/products/{product}', [ProductController::class, 'show'])
        ->name('manager.products.show');

    Route::get('/manager/stock', [StockController::class, 'manager'])
        ->name('manager.stock');
    Route::post('/manager/stock/opname', [StockController::class, 'managerOpname'])
        ->name('manager.stock.opname');

    Route::get('/manager/transactions/create', [TransactionController::class, 'managerCreate'])
        ->name('manager.transactions.create');
    Route::post('/manager/transactions', [TransactionController::class, 'managerStore'])
        ->name('manager.transactions.store');

    Route::get('/manager/transactions/{transaction}', [TransactionController::class, 'show'])
        ->name('manager.transactions.show');

    Route::get('/manager/supplier', [SupplierController::class, 'manager'])
        ->name('manager.supplier');

    Route::get('/manager/laporan', [ReportController::class, 'managerIndex'])
        ->name('manager.laporan');
});

// ==========================================
// 6. STAFF GUDANG
// ==========================================
Route::middleware(['auth', 'role:Staff Gudang'])->group(function () {
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'Index'])
        ->name('staff.dashboard');

    Route::get('/staff/stock', [StaffDashboardController::class, 'Stock'])
        ->name('staff.stock');

    Route::patch('/staff/transactions/{transaction}/confirm', [StaffDashboardController::class, 'confirmTransaction'])
        ->name('staff.transactions.confirm');

    Route::patch('/staff/transactions/{transaction}/reject', [StaffDashboardController::class, 'rejectTransaction'])
        ->name('staff.transactions.reject');
});
