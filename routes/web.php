<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\ProfilIkmController;
use App\Http\Controllers\DashboardController as UserDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\isUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('rumah-batik', [HomeController::class, 'rumahBatik'])->name('rumah-batik');
// Route::get('produk-ikm', [HomeController::class, 'produkIkm'])->name('produk-ikm');
// Route::get('produk-ikm/kategori/{slug}', [HomeController::class, 'produkByKategori'])->name('produk-ikm.kategori');
// Route::get('produk-ikm/{id}', [HomeController::class, 'produkIkmDetail'])->name('produk-ikm.detail');
// Route::get('outlet-ikm', [HomeController::class, 'outletIkm'])->name('outlet-ikm');
// Route::get('profil-ikm', [HomeController::class, 'profilIkm'])->name('profil-ikm');
// Route::get('profil-ikm/{id}', [HomeController::class, 'profilIkmDetail'])->name('profil-ikm.detail');

//  Auth::routes();

// Route::prefix('user-area')->middleware(['auth', isUser::class])->name('user.')->group(function () {
//     Route::get('/', function () {
//         return redirect()->route('user.home');
//     });
//     Route::get('dashboard', [UserDashboardController::class, 'index'])->name('home');
//     Route::resource('outlet', OutletController::class);
//     Route::resource('produk', ProdukController::class);

//     Route::get('profile', [UserController::class, 'editProfile'])->name('profile.edit');
//     Route::put('profile', [UserController::class, 'updateProfile'])->name('profile.update');
// });

// Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
//     Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

   
//     Route::resource('kategori', KategoriController::class);

    
//     Route::get('profil-ikm', [ProfilIkmController::class, 'index'])->name('profil-ikm.index');
//     Route::get('profil-ikm/{id}', [ProfilIkmController::class, 'show'])->name('profil-ikm.show');
//     Route::get('profil-ikm/{id}/edit', [ProfilIkmController::class, 'edit'])->name('profil-ikm.edit');
//     Route::put('profil-ikm/{id}', [ProfilIkmController::class, 'update'])->name('profil-ikm.update');
// });
//  Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
