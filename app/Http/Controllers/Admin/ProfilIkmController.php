<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilIkm;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ProfilIkmController extends Controller
{
    public function index()
    {
        $profilIkms = User::where('role', 'Pengguna')
            ->with(['profilIkm'])
            ->orderBy('id', 'desc')
            ->get();
        return view('pages.admin.profil-ikm.index', compact('profilIkms'));
    }

    public function show($id)
    {
        $profilIkm = User::with(['profilIkm'])->findOrFail($id);
        return view('pages.admin.profil-ikm.show', compact('profilIkm'));
    }

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

    public function update(Request $request, $id)
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
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
}
