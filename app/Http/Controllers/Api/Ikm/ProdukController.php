<?php

namespace App\Http\Controllers\Api\Ikm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class ProdukController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();

            $produk = Produk::where('ikm_id', $user->ikm_id)
                ->with('ikm.kategori')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $produk
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user->ikm_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus memiliki profil IKM terlebih dahulu'
            ], 403);
        }

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'jenis_produk' => 'required|string|max:255',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
            'berat' => 'required|integer',
            'deskripsi' => 'required|string',
            'varian' => 'nullable|string|max:255',
            'ukuran' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        try {
            $fotoPath = null;

            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('produk', 'public');
            }

            $produk = Produk::create([
                'ikm_id' => $user->ikm_id,
                'nama_produk' => $request->nama_produk,
                'jenis_produk' => $request->jenis_produk,
                'harga' => $request->harga,
                'stok' => $request->stok,
                'berat' => $request->berat,
                'deskripsi' => $request->deskripsi,
                'varian' => $request->varian,
                'ukuran' => $request->ukuran,
                'foto' => $fotoPath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'data' => $produk
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $produk = Produk::with('ikm')->findOrFail($id);

            if ($produk->ikm_id !== $user->ikm_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $produk
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $produk = Produk::findOrFail($id);

            if ($produk->ikm_id !== $user->ikm_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $request->validate([
                'nama_produk' => 'required|string|max:255',
                'jenis_produk' => 'required|string|max:255',
                'harga' => 'required|integer',
                'stok' => 'required|integer',
                'berat' => 'required|integer',
                'deskripsi' => 'required|string',
                'varian' => 'nullable|string|max:255',
                'ukuran' => 'nullable|string|max:255',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
                'remove_image' => 'nullable|boolean',
            ]);

            $data = $request->only([
                'nama_produk',
                'jenis_produk',
                'harga',
                'stok',
                'berat',
                'deskripsi',
                'varian',
                'ukuran'
            ]);

            if ($request->remove_image) {
                if ($produk->foto) {
                    Storage::disk('public')->delete($produk->foto);
                }
                $data['foto'] = null;
            } elseif ($request->hasFile('foto')) {
                if ($produk->foto) {
                    Storage::disk('public')->delete($produk->foto);
                }
                $data['foto'] = $request->file('foto')->store('produk', 'public');
            }

            $produk->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui',
                'data' => $produk
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $produk = Produk::findOrFail($id);

            if ($produk->ikm_id !== $user->ikm_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }

            $produk->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
