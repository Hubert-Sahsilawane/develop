<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PelangganApiController;
use App\Http\Controllers\Api\ProdukApiController;
use App\Http\Controllers\Api\TransaksiApiController;
use App\Http\Controllers\Api\LaporanApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\KategoriApiController;
use App\Models\Kategori;
use Illuminate\Http\Request;

//Login API
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/home', function (Request $request) {
    return response()->json(['message' => 'Selamat datang di halaman home', 'user' => $request->user()]);
});

//logout API
Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logout berhasil']);
})->middleware('auth:sanctum');


Route::get('/kategori', [KategoriApiController::class, 'index']);
Route::post('/kategori', [KategoriApiController::class, 'store']);
Route::get('/kategori/{id}', [KategoriApiController::class, 'show']);
Route::put('/kategori/{id}', [KategoriApiController::class, 'update']);
Route::delete('/kategori/{id}', [KategoriApiController::class, 'destroy']);

// PELANGGAN (CRUD via API)
Route::get('/pelanggan', [PelangganApiController::class, 'index']);
Route::post('/pelanggan', [PelangganApiController::class, 'store']);
Route::get('/pelanggan/{id}', [PelangganApiController::class, 'show']);
Route::put('/pelanggan/{id}', [PelangganApiController::class, 'update']);
Route::delete('/pelanggan/{id}', [PelangganApiController::class, 'destroy']);

// PRODUK (CRUD via API)
Route::get('/produk', [ProdukApiController::class, 'index']);
Route::post('/produk', [ProdukApiController::class, 'store']);
Route::get('/produk/{id}', [ProdukApiController::class, 'show']);
Route::put('/produk/{id}', [ProdukApiController::class, 'update']);
Route::delete('/produk/{id}', [ProdukApiController::class, 'destroy']);

// TRANSAKSI
Route::get('/transaksi', [TransaksiApiController::class, 'index']); // jika ada list
Route::post('/transaksi', [TransaksiApiController::class, 'store']);
Route::get('/transaksi/{id}/struk', [TransaksiApiController::class, 'printStruk']);

// LAPORAN PENJUALAN
Route::get('/laporan', [LaporanApiController::class, 'index']);
Route::delete('/penjualan/{id}', [LaporanApiController::class, 'destroy']);


