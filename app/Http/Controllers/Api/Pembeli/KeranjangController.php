<?php

namespace App\Http\Controllers\Api\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\Keranjang;
use App\Models\DetailKeranjang;
use App\Models\Produk;

class KeranjangController extends Controller
{
    /**
     * Ambil keranjang aktif pembeli
     */
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();

            $keranjang = Keranjang::with('detail.produk')
                ->where('pembeli_id', $user->pembeli_id)
                ->where('status', 'aktif')
                ->first();

            $totalItem = 0;

            if ($keranjang && $keranjang->detail) {
                // kalau mau jumlah produk (qty)
                $totalItem = $keranjang->detail->sum('qty');

                // kalau mau jumlah jenis produk:
                // $totalItem = $keranjang->detail->count();
            }

            return response()->json([
                'success' => true,
                'data' => $keranjang,
                'total_item' => $totalItem
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil keranjang',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tambah produk ke keranjang
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            $request->validate([
                'produk_id' => 'required|exists:produk,id',
                'qty' => 'required|integer|min:1'
            ]);

            $produk = Produk::findOrFail($request->produk_id);

            // cek keranjang aktif
            $keranjang = Keranjang::where('pembeli_id', $user->pembeli_id)
                ->where('status', 'aktif')
                ->first();

            /**
             * Jika ada keranjang & beda IKM → tolak
             */
            if ($keranjang && $keranjang->ikm_id != $produk->ikm_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang hanya boleh berisi produk dari 1 IKM'
                ], 409);
            }

            /**
             * Jika belum ada → buat keranjang
             */
            if (!$keranjang) {
                $keranjang = Keranjang::create([
                    'pembeli_id' => $user->pembeli_id,
                    'ikm_id' => $produk->ikm_id,
                    'status' => 'aktif'
                ]);
            }

            /**
             * Cek produk sudah ada?
             */
            $detail = DetailKeranjang::where('keranjang_id', $keranjang->id)
                ->where('produk_id', $produk->id)
                ->first();

            if ($detail) {
                $detail->qty += $request->qty;
                $detail->save();
            } else {
                DetailKeranjang::create([
                    'keranjang_id' => $keranjang->id,
                    'produk_id' => $produk->id,
                    'qty' => $request->qty,
                    'harga' => $produk->harga
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Produk ditambahkan ke keranjang'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan ke keranjang',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update qty item keranjang
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'qty' => 'required|integer|min:1'
            ]);

            $detail = DetailKeranjang::findOrFail($id);
            $detail->qty = $request->qty;
            $detail->save();

            return response()->json([
                'success' => true,
                'message' => 'Qty berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update qty',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus item keranjang
     */
    public function destroy($id): JsonResponse
    {
        try {
            $detail = DetailKeranjang::findOrFail($id);

            // 🔥 ambil keranjang_id sebelum delete
            $keranjangId = $detail->keranjang_id;

            $detail->delete();

            // 🔥 cek apakah masih ada item di keranjang
            $sisaItem = DetailKeranjang::where('keranjang_id', $keranjangId)->count();

            if ($sisaItem === 0) {
                // 🔥 hapus keranjang kalau kosong
                Keranjang::where('id', $keranjangId)->delete();

                // ALTERNATIF:
                // Keranjang::where('id', $keranjangId)->update(['status' => 'selesai']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari keranjang'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus item',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
