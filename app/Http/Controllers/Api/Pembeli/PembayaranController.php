<?php

namespace App\Http\Controllers\Api\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use App\Mail\NotifikasiPesanan;

class PembayaranController extends Controller
{
    /**
     * Upload bukti transfer
     */
    public function upload(Request $request, $pesanan_id): JsonResponse
    {
        try {
            $request->validate([
                'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            $user = Auth::user();

            $pesanan = Pesanan::with('pembayaran')
                ->where('id', $pesanan_id)
                ->where('pembeli_id', $user->pembeli_id)
                ->firstOrFail();

            /**
             * Validasi pesanan
             */
            if ($pesanan->status_pesanan === 'batal') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan sudah dibatalkan'
                ], 400);
            }

            if (
                $pesanan->pembayaran &&
                $pesanan->pembayaran->status_pembayaran === 'valid'
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran sudah divalidasi'
                ], 400);
            }

            /**
             * Upload file
             */
            $file = $request->file('bukti_transfer');
            $path = $file->store('bukti_transfer', 'public');

            /**
             * Hapus bukti lama (jika ada)
             */
            if ($pesanan->pembayaran && $pesanan->pembayaran->bukti_transfer) {
                Storage::disk('public')->delete($pesanan->pembayaran->bukti_transfer);
            }

            /**
             * Update / Create pembayaran
             */
            $pembayaran = Pembayaran::updateOrCreate(
                ['pesanan_id' => $pesanan->id],
                [
                    'bukti_transfer' => $path,
                    'status_pembayaran' => 'pending',
                    'keterangan' => null
                ]
            );

            /**
             * 🔥 Kirim email notifikasi ke IKM
             */
            $userIKM = User::where('ikm_id', $pesanan->ikm_id)->first();

            if ($userIKM && $userIKM->email) {

                $mailData = [
                    'title' => 'Notifikasi Pesanan Baru',
                    'ikm_nama' => $userIKM->name,
                    'pesanan_id' => $pesanan->id,
                    'total_bayar' => $pesanan->total_bayar,
                    'metode_pengiriman' => $pesanan->metode_pengiriman,
                    'status_pesanan' => $pesanan->status_pesanan,
                    'status_pembayaran' => $pembayaran->status_pembayaran,
                    'bukti_transfer_url' => asset('storage/' . $pembayaran->bukti_transfer),
                ];

                Mail::to($userIKM->email)
                    ->send(new NotifikasiPesanan($mailData));
            }

            return response()->json([
                'success' => true,
                'message' => 'Bukti transfer berhasil diupload',
                'data' => [
                    'id' => $pembayaran->id,
                    'status_pembayaran' => $pembayaran->status_pembayaran,
                    'bukti_transfer' => $pembayaran->bukti_transfer,
                    'bukti_transfer_url' => $pembayaran->bukti_transfer_url,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload bukti gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lihat status pembayaran
     */
    public function status($pesanan_id): JsonResponse
    {
        try {
            $user = Auth::user();

            $pesanan = Pesanan::with('pembayaran')
                ->where('id', $pesanan_id)
                ->where('pembeli_id', $user->pembeli_id)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $pesanan->pembayaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil status pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function detailPembayaran($pesanan_id): JsonResponse
    {
        try {
            $user = Auth::user();

            // Ambil pesanan beserta IKM
            $pesanan = Pesanan::with('ikm')
                ->where('id', $pesanan_id)
                ->where('pembeli_id', $user->pembeli_id)
                ->firstOrFail();

            $ikm = $pesanan->ikm;

            // Data rekening IKM
            $ikmRekening = null;
            if ($ikm) {
                $ikmRekening = [
                    'nama_rekening' => $ikm->nama_rekening,
                    'no_rekening' => $ikm->no_rekening,
                    'jenis_rekening' => $ikm->jenis_rekening,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail pembayaran berhasil diambil',
                'data' => [
                    'pesanan_id' => $pesanan->id,
                    'total_bayar' => $pesanan->total_bayar,
                    'metode_pengiriman' => $pesanan->metode_pengiriman,
                    'ikm_rekening' => $ikmRekening,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail pembayaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
