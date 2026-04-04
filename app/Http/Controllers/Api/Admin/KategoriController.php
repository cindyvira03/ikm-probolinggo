<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KategoriController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();

            return response()->json([
                'success' => true,
                'data' => $kategoris
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori'
        ]);

        try {
            $kategori = Kategori::create([
                'nama_kategori' => $request->nama_kategori
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $kategori
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $kategori = Kategori::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $id
        ]);

        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->update([
                'nama_kategori' => $request->nama_kategori
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diperbarui',
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
