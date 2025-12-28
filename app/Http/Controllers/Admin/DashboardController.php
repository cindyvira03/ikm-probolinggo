<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutletIkm;
use App\Models\Produk;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahIKM = User::where('role', 'Pengguna')->count();
        $jumlahProduk = Produk::count();
        $jumlahOutlet = OutletIkm::count();
        return view('pages.admin.dashboard', compact('jumlahIKM', 'jumlahProduk', 'jumlahOutlet'));
    }
}
