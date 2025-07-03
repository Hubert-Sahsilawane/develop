<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| AUTH GUEST (login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| HOME REDIRECTOR (setelah login)
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    return view('home');
})->middleware('auth')->name('home');

/*
|--------------------------------------------------------------------------
| DASHBOARD UTAMA (semua role)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN & KASIR (akses semua route produk)
|--------------------------------------------------------------------------
| Kasir boleh CRUD, jadi tidak perlu pisahkan index saja
*/
Route::middleware(['auth', 'role:admin,kasir,owner'])->group(function () {
    Route::resource('produk', ProdukController::class);
});

/*
|--------------------------------------------------------------------------
| ADMIN SAJA (CRUD pelanggan dan users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,owner'])->group(function () {
    Route::resource('pelanggan', PelangganController::class)->except(['index']);
    Route::resource('users', UserController::class);
});

/*
|--------------------------------------------------------------------------
| ADMIN & KASIR (akses index pelanggan, transaksi, laporan)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,kasir,owner'])->group(function () {
    // Pelanggan: hanya lihat (index)
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');

    // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{id}/struk', [TransaksiController::class, 'printStruk'])->name('transaksi.printStruk');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::delete('/penjualan/{id}', [LaporanController::class, 'destroy'])->name('penjualan.destroy');
});
