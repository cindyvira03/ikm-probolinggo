<?php

namespace App\Http\Controllers\API\Ikm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;

class RiwayatPesananController extends Controller
{
    /**
     * Riwayat pesanan milik IKM
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Pesanan::where('ikm_id', $user->ikm_id)
            ->with(['pembeli', 'detail.produk', 'pengiriman', 'pembayaran', 'alamat']);

        // FILTER STATUS
        if ($request->status) {
            $query->where('status_pesanan', $request->status);
        }

        $pesanan = $query
            ->latest()
            ->paginate(10);

        return response()->json($pesanan);
    }

    /**
     * Tandai pesanan sebagai DIKIRIM
     */
    public function kirim(Request $request, $id)
    {
        $request->validate([
            'no_resi' => 'required|string|max:100'
        ]);

        $user = Auth::user();

        $pesanan = Pesanan::where('ikm_id', $user->ikm_id)
            ->with('pengiriman')
            ->findOrFail($id);

        // Validasi status harus diproses
        if ($pesanan->status_pesanan !== 'diproses') {
            return response()->json([
                'message' => 'Pesanan hanya bisa dikirim jika status DIPROSES'
            ], 422);
        }

        // Update status → dikirim
        $pesanan->update([
            'status_pesanan' => 'dikirim'
        ]);

        // Update / buat data pengiriman
        if ($pesanan->pengiriman) {
            $pesanan->pengiriman->update([
                'no_resi' => $request->no_resi
            ]);
        }

        return response()->json([
            'message' => 'Pesanan berhasil dikirim'
        ]);
    }
}
