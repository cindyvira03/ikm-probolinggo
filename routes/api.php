<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\KategoriController;
use App\Http\Controllers\Api\Admin\ProfilIkmController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Ikm\DashboardController as DashboardIkmController;
use App\Http\Controllers\Api\Ikm\IkmController;
use App\Http\Controllers\Api\Ikm\ProdukController;
use App\Http\Controllers\Api\Ikm\OutletController;
use App\Http\Controllers\Api\Pembeli\PembeliController;
use App\Http\Controllers\Api\Pembeli\KeranjangController;
use App\Http\Controllers\Api\Pembeli\PesananController;
use App\Http\Controllers\Api\Pembeli\PembayaranController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\Ikm\RiwayatPesananController;
use App\Http\Controllers\API\Ikm\ValidasiPembayaranController;
use App\Http\Controllers\Api\SeoSettingController;
use App\Http\Controllers\Api\CmsPageController;

Route::get('/ping', function () {
    return 'pong';
});
// AUTH
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register/ikm', [AuthController::class, 'registerIkm']);
Route::post('/register/pembeli', [AuthController::class, 'registerPembeli']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/confirm-password', [AuthController::class, 'confirmPassword']);
});

// HALAMAN UTAMA
Route::get('/produk-ikm', [HomeController::class, 'produkIkm']);
Route::get('/produk-ikm/kategori/{slug}', [HomeController::class, 'produkByKategori']);
Route::get('/produk-ikm/{id}', [HomeController::class, 'produkDetail']);

Route::get('/outlet-ikm', [HomeController::class, 'outletIkm']);
Route::get('/profil-ikm', [HomeController::class, 'profilIkm']);
Route::get('/profil-ikm/{id}', [HomeController::class, 'profilIkmDetail']);

Route::get('/seo', [SeoSettingController::class, 'index']);

// HALAMAN ADMIN
Route::prefix('admin')
    ->middleware(['auth:sanctum', 'role:admin'])
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index']);

        Route::get('/kategori', [KategoriController::class, 'index']);
        Route::post('/kategori', [KategoriController::class, 'store']);
        Route::get('/kategori/{id}', [KategoriController::class, 'show']);
        Route::put('/kategori/{id}', [KategoriController::class, 'update']);
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);

        Route::get('/profil-ikm', [ProfilIkmController::class, 'index']);
        Route::get('/profil-ikm/{id}', [ProfilIkmController::class, 'show']);
        Route::get('/profil-ikm/{id}/edit', [ProfilIkmController::class, 'edit']);
        Route::put('/profil-ikm/{id}', [ProfilIkmController::class, 'update']);

        Route::get('/seo', [SeoSettingController::class, 'index']);
        Route::post('/seo', [SeoSettingController::class, 'update']);

        Route::get('/cms', [CmsPageController::class, 'index']);
        Route::post('/cms', [CmsPageController::class, 'update']);
    });

// HALAMAN IKM
Route::prefix('ikm')
    ->middleware(['auth:sanctum', 'role:ikm'])
    ->group(function () {

        Route::get('/', [DashboardIkmController::class, 'index']);

        Route::get('/profile', [IkmController::class, 'profile']);
        Route::post('/profile/update', [IkmController::class, 'update']);

        Route::get('/produk', [ProdukController::class, 'index']);       // list produk IKM login
        Route::post('/produk', [ProdukController::class, 'store']);      // tambah produk
        Route::get('/produk/{id}', [ProdukController::class, 'show']);   // detail produk
        Route::put('/produk/{id}', [ProdukController::class, 'update']); // update produk
        Route::delete('/produk/{id}', [ProdukController::class, 'destroy']); // hapus produk

        Route::get('/outlet', [OutletController::class, 'index']);
        Route::post('/outlet', [OutletController::class, 'store']);
        Route::get('/outlet/{id}', [OutletController::class, 'show']);
        Route::put('/outlet/{id}', [OutletController::class, 'update']);
        Route::delete('/outlet/{id}', [OutletController::class, 'destroy']);

        Route::get('pembayaran', [ValidasiPembayaranController::class, 'index']);
        Route::post('pembayaran/{id}/validasi', [ValidasiPembayaranController::class, 'validasi']);

        Route::get('pesanan', [RiwayatPesananController::class, 'index']);
        Route::post('pesanan/{id}/kirim', [RiwayatPesananController::class, 'kirim']);

        Route::get('/rajaongkir/provinces', [OutletController::class, 'provinces']);
        Route::get('/rajaongkir/cities/{provinceId}', [OutletController::class, 'cities']);
        Route::get('/rajaongkir/districts/{cityId}', [OutletController::class, 'districts']);
    });

// HALAMAN PEMBELI
Route::prefix('pembeli')
    ->middleware(['auth:sanctum', 'role:pembeli'])
    ->group(function () {

        Route::get('/profile', [PembeliController::class, 'profile']);
        Route::put('/profile', [PembeliController::class, 'update']);

        Route::get('/keranjang', [KeranjangController::class, 'index']);
        Route::post('/keranjang', [KeranjangController::class, 'store']);
        Route::put('/keranjang/{id}', [KeranjangController::class, 'update']);
        Route::delete('/keranjang/{id}', [KeranjangController::class, 'destroy']);

        Route::get('/rajaongkir/provinces', [PesananController::class, 'provinces']);
        Route::get('/rajaongkir/cities/{provinceId}', [PesananController::class, 'cities']);
        Route::get('/rajaongkir/districts/{cityId}', [PesananController::class, 'districts']);

        Route::post('/checkout-page', [PesananController::class, 'checkoutPage']);
        Route::post('/checkout', [PesananController::class, 'checkout']);
        Route::get('/pesanan', [PesananController::class, 'index']);
        Route::patch('/pesanan/{id}/selesai', [PesananController::class, 'selesai']);

        Route::post('pesanan/{id}/upload-pembayaran', [PembayaranController::class, 'upload']);
        Route::get('pesanan/{id}/status-pembayaran', [PembayaranController::class, 'status']);
        Route::get('/pesanan/{id}/detail-pembayaran', [PembayaranController::class, 'detailPembayaran']);
    });
