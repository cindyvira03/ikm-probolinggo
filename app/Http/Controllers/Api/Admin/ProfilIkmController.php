<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilIkm;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProfilIkmController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $profilIkms = User::where('role', 'ikm')
                ->with('profilIkm.kategori')
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $profilIkms
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data profil IKM',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $profilIkm = User::with([
                'profilIkm.kategori',
                'profilIkm.outlets',
                'profilIkm.produk'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $profilIkm
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Profil IKM tidak ditemukan'
            ], 404);
        }
    }

    // 👉 ini MENYAMAKAN dengan kode lama (edit)
    public function edit($id): JsonResponse
    {
        try {
            $profilIkm = ProfilIkm::with('kategori')->findOrFail($id);
            $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();

            return response()->json([
                'success' => true,
                'data' => $profilIkm,
                'kategoris' => $kategoris
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Profil IKM tidak ditemukan'
            ], 404);
        }
    }

    // 👉 ini MENYAMAKAN dengan kode lama (update)
    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,aktif,tidak_aktif'
        ]);

        try {
            $profilIkm = ProfilIkm::findOrFail($id);
            $profilIkm->status = $request->status;
            $profilIkm->save();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
