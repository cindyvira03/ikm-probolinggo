<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProfilIkm;
use App\Models\Kategori;
use App\Models\OutletIkm;

class HomeController extends Controller
{
    public function index()
    {
        // return view('pages.home.index');
        return redirect()->route('produk-ikm');
    }

    public function rumahBatik()
    {
        return redirect()->route('produk-ikm');
        // return view('pages.home.rumah-batik');
    }

    public function produkIkm()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        $produk = Produk::with('ikm.kategori')->get();
        return view('pages.home.produk-ikm', compact('produk', 'kategori'));
    }

    public function produkByKategori($slug)
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        $currentKategori = Kategori::where('slug', $slug)->firstOrFail();
        $produk = Produk::with('ikm.kategori')
            ->whereHas('ikm', function ($query) use ($currentKategori) {
                $query->where('kategori_id', $currentKategori->id);
            })
            ->get();

        return view('pages.home.produk-ikm', compact('produk', 'kategori', 'currentKategori'));
    }

    public function produkIkmDetail($id)
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        $produk = Produk::with('ikm.kategori')->findOrFail($id);
        return view('pages.home.produk-ikm-detail', compact('produk', 'kategori'));
    }

    public function outletIkm()
    {
        $outlet = OutletIkm::get();
        return view('pages.home.outlet-ikm', compact('outlet'));
    }

    public function profilIkm()
    {
        $ikm = ProfilIkm::with('kategori')->get();
        return view('pages.home.profil-ikm', compact('ikm'));
    }

    public function profilIkmDetail($id)
    {
        $ikm = ProfilIkm::with('kategori')->findOrFail($id);
        return view('pages.home.profil-ikm-detail', compact('ikm'));
    }
}
