<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\ProfilIkm;
use App\Models\Kategori;
use App\Models\OutletIkm;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function produkIkm(): JsonResponse
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();

        $produk = Produk::with('ikm.kategori')
            ->whereHas('ikm', function ($q) {
                $q->aktif();
            })
            ->get();

        return response()->json([
            'success' => true,
            'kategori' => $kategori,
            'produk' => $produk
        ]);
    }

    public function produkByKategori($slug): JsonResponse
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();

        $currentKategori = Kategori::where('slug', $slug)->firstOrFail();

        $produk = Produk::with('ikm.kategori')
            ->whereHas('ikm', function ($query) use ($currentKategori) {
                $query->where('kategori_id', $currentKategori->id)
                    ->aktif();
            })
            ->get();

        return response()->json([
            'success' => true,
            'kategori' => $kategori,
            'currentKategori' => $currentKategori,
            'produk' => $produk
        ]);
    }

    public function produkDetail($id): JsonResponse
    {
        $produk = Produk::with('ikm.kategori')
            ->whereHas('ikm', function ($q) {
                $q->aktif();
            })
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'produk' => $produk
        ]);
    }

    public function outletIkm(): JsonResponse
    {
        // Ambil outlet dengan relasi profilIkm dan kategori
        $outlets = OutletIkm::with('profilIkm.kategori')
            ->whereHas('profilIkm', function ($q) {
                $q->aktif(); // scope aktif() di ProfilIkm
            })
            ->get();

        // Format JSON supaya sesuai TS di Next.js
        $data = $outlets->map(function ($item) {
            return [
                'id' => $item->id,
                'alamat' => $item->alamat,
                'lokasi_googlemap' => $item->lokasi_googlemap,
                'foto_lokasi_tampak_depan' => $item->foto_lokasi_tampak_depan,
                'cara_order' => $item->cara_order,
                'provinsi' => $item->provinsi,
                'kota_kab' => $item->kota_kab,
                'kecamatan' => $item->kecamatan,
                'profilIkm' => $item->profilIkm ? [
                    'nama_usaha' => $item->profilIkm->nama_usaha,
                    'kategori' => $item->profilIkm->kategori ? [
                        'nama_kategori' => $item->profilIkm->kategori->nama_kategori
                    ] : null
                ] : null
            ];
        });

        return response()->json([
            'success' => true,
            'outlet' => $data
        ]);
    }

    public function profilIkm(): JsonResponse
    {
        $ikm = ProfilIkm::with('kategori')
            ->aktif()
            ->get();

        return response()->json([
            'success' => true,
            'ikm' => $ikm
        ]);
    }

    public function profilIkmDetail($id): JsonResponse
    {
        $ikm = ProfilIkm::with('kategori', 'outlets', 'produk')
            ->aktif()
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'ikm' => $ikm
        ]);
    }
}
