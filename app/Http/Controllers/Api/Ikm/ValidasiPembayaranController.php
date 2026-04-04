<?php

namespace App\Http\Controllers\API\Ikm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;

class ValidasiPembayaranController extends Controller
{
    /**
     * List pembayaran masuk ke IKM
     */
    public function index()
    {
        $user = Auth::user();

        $pembayaran = Pembayaran::whereHas('pesanan', function ($q) use ($user) {
            $q->where('ikm_id', $user->ikm_id);
        })
            ->with('pesanan')
            ->latest()
            ->get();

        return response()->json([
            'data' => [
                'id' => $pembayaran->id,
                'status_pembayaran' => $pembayaran->status_pembayaran,
                'bukti_transfer' => $pembayaran->bukti_transfer,
                'bukti_transfer_url' => $pembayaran->bukti_transfer_url,
            ]
        ]);
    }

    /**
     * Validasi pembayaran (valid / ditolak)
     */
    public function validasi(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:valid,ditolak',
            'keterangan' => 'nullable|string|max:255'
        ]);

        if ($request->status_pembayaran === 'ditolak' && !$request->keterangan) {
            return response()->json([
                'message' => 'Keterangan wajib diisi jika pembayaran ditolak'
            ], 422);
        }

        $user = Auth::user();

        $pembayaran = Pembayaran::whereHas('pesanan', function ($q) use ($user) {
            $q->where('ikm_id', $user->ikm_id);
        })
            ->with('pesanan.detail.produk')
            ->findOrFail($id);

        $pembayaran->update([
            'status_pembayaran' => $request->status_pembayaran,
            'keterangan' => $request->status_pembayaran === 'ditolak'
                ? $request->keterangan
                : null
        ]);

        // Jika valid → pesanan diproses
        if ($request->status_pembayaran === 'valid') {
            $pembayaran->pesanan->update([
                'status_pesanan' => 'diproses'
            ]);
        } elseif ($request->status_pembayaran === 'ditolak') {
            // ✅ TAMBAHAN: BALIKIN STOK
            if ($pembayaran->pesanan->status_pesanan !== 'batal') {
                foreach ($pembayaran->pesanan->detail as $item) {
                    if ($item->produk) {
                        $item->produk->increment('stok', $item->qty);
                    }
                }
            }
            $pembayaran->pesanan->update([
                'status_pesanan' => 'batal'
            ]);
        }

        return response()->json([
            'message' => 'Pembayaran berhasil divalidasi'
        ]);
    }
}
