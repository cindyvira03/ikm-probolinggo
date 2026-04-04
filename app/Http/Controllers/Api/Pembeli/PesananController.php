<?php

namespace App\Http\Controllers\Api\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\AlamatPengiriman;
use App\Models\OutletIkm;
use App\Models\Pengiriman;
use Illuminate\Support\Facades\Http;
use App\Services\RajaOngkirService;

class PesananController extends Controller
{
    private RajaOngkirService $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    public function provinces(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->rajaOngkir->getProvinces()
        ]);
    }

    public function cities($provinceId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->rajaOngkir->getCities($provinceId)
        ]);
    }

    public function districts($cityId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->rajaOngkir->getDistricts($cityId)
        ]);
    }

    private function generateNoPesanan()
    {
        $date = date('Ymd');

        // ambil pesanan terakhir hari ini
        $lastOrder = \App\Models\Pesanan::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            // ambil angka terakhir
            $lastNumber = (int) substr($lastOrder->no_pesanan, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'ORD-' . $date . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function checkoutPage(Request $request): JsonResponse
    {
        try {

            $user = Auth::user();

            $request->validate([
                'metode_pengiriman' => 'required|in:diambil,dikirim',
                'kurir' => 'required_if:metode_pengiriman,dikirim',
                'provinsi' => 'required_if:metode_pengiriman,dikirim',
                'kota_kab' => 'required_if:metode_pengiriman,dikirim',
                'kecamatan' => 'required_if:metode_pengiriman,dikirim',
            ]);

            /**
             * ==============================
             * Ambil Keranjang Aktif
             * ==============================
             */
            $keranjang = Keranjang::with(['detail.produk', 'ikm'])
                ->where('pembeli_id', $user->pembeli_id)
                ->where('status', 'aktif')
                ->first();

            if (!$keranjang || $keranjang->detail->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong'
                ], 400);
            }

            /**
             * ==============================
             * Hitung Subtotal
             * ==============================
             */
            $subtotal = $keranjang->detail->sum(
                fn($item) => $item->qty * $item->harga
            );

            /**
             * ==============================
             * Ambil Outlet
             * ==============================
             */
            $outlets = OutletIkm::where('ikm_id', $keranjang->ikm_id)->get();

            if ($outlets->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Outlet tidak ditemukan'
                ], 404);
            }

            $biayaOngkir = 0;
            $totalBayar = $subtotal;
            $dataOutlet = null;

            /**
             * ==============================
             * METODE DIAMBIL
             * ==============================
             */
            if ($request->metode_pengiriman === 'diambil') {

                $dataOutlet = $outlets->map(function ($o) {
                    return [
                        'id' => $o->id,
                        'provinsi' => $o->provinsi,
                        'kota_kab' => $o->kota_kab,
                        'kecamatan' => $o->kecamatan,
                        'lokasi_googlemap' => $o->lokasi_googlemap,
                    ];
                });
            }

            /**
             * ==============================
             * METODE DIKIRIM
             * ==============================
             */
            if ($request->metode_pengiriman === 'dikirim') {

                $outlet = $outlets->first(); // <-- tetap 1 saja

                $beratTotal = $keranjang->detail->sum(
                    fn($item) => $item->qty * ($item->produk->berat ?? 100)
                );

                // ORIGIN (Outlet)
                $originProvinceId = $this->rajaOngkir->getProvinceId($outlet->provinsi);
                $originCityId = $this->rajaOngkir->getCityId($outlet->kota_kab, $originProvinceId);
                $originDistrictId = $this->rajaOngkir->getDistrictId($outlet->kecamatan, $originCityId);

                // DESTINATION (Pembeli)
                $destinationProvinceId = $this->rajaOngkir->getProvinceId($request->provinsi);
                $destinationCityId = $this->rajaOngkir->getCityId($request->kota_kab, $destinationProvinceId);
                $destinationDistrictId = $this->rajaOngkir->getDistrictId($request->kecamatan, $destinationCityId);

                if (!$originDistrictId || !$destinationDistrictId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'District ID tidak ditemukan'
                    ], 422);
                }

                $biayaOngkir = $this->rajaOngkir->calculateDistrictCost(
                    $originDistrictId,
                    $destinationDistrictId,
                    $beratTotal,
                    $request->kurir
                );

                if (!$biayaOngkir) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal menghitung ongkir'
                    ], 422);
                }

                $totalBayar = $subtotal + $biayaOngkir;
            }

            /**
             * ==============================
             * RESPONSE
             * ==============================
             */
            return response()->json([
                'success' => true,
                'message' => 'Data checkout page',
                'data' => [
                    'subtotal' => $subtotal,
                    'ongkir' => $biayaOngkir,
                    'total_bayar' => $totalBayar,
                    'outlets' => $dataOutlet,
                    // ✅ tambahkan data IKM
                    'ikm_rekening' => [
                        'no_rekening' => $keranjang->ikm->no_rekening,
                        'jenis_rekening' => $keranjang->ikm->jenis_rekening,
                        'nama_rekening' => $keranjang->ikm->nama_rekening,
                    ],
                ]
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat checkout page',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * CHECKOUT + ONGKIR
     */
    public function checkout(Request $request): JsonResponse
    {
        try {

            $user = Auth::user();

            $request->validate([
                'metode_pengiriman' => 'required|in:diambil,dikirim',

                'kurir' => 'required_if:metode_pengiriman,dikirim',

                'nama_penerima' => 'required_if:metode_pengiriman,dikirim',
                'no_hp' => 'required_if:metode_pengiriman,dikirim',
                'provinsi' => 'required_if:metode_pengiriman,dikirim',
                'kota_kab' => 'required_if:metode_pengiriman,dikirim',
                'kecamatan' => 'required_if:metode_pengiriman,dikirim',
                'kode_pos' => 'required_if:metode_pengiriman,dikirim',
                'alamat_lengkap' => 'required_if:metode_pengiriman,dikirim',
            ]);

            $keranjang = Keranjang::with(['detail.produk', 'ikm'])
                ->where('pembeli_id', $user->pembeli_id)
                ->where('status', 'aktif')
                ->first();

            if (!$keranjang || $keranjang->detail->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong'
                ], 400);
            }

            foreach ($keranjang->detail as $item) {
                if ($item->produk->stok < $item->qty) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi untuk produk ' . $item->produk->nama_produk
                    ], 400);
                }
            }

            $subtotal = $keranjang->detail->sum(
                fn($item) => $item->qty * $item->harga
            );

            $biayaOngkir = 0;
            $outletId = null;

            $outlets = OutletIkm::where('ikm_id', $keranjang->ikm_id)->get();

            if ($outlets->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Outlet IKM tidak tersedia'
                ], 404);
            }

            // 🔥 selalu pakai outlet pertama
            $outlet = $outlets->first();
            $outletId = $outlet->id;

            /**
             * ==============================
             * METODE DIKIRIM
             * ==============================
             */
            if ($request->metode_pengiriman === 'dikirim') {

                $beratTotal = $keranjang->detail->sum(
                    fn($item) => $item->qty * ($item->produk->berat ?? 100)
                );

                // ORIGIN
                $originProvinceId = $this->rajaOngkir->getProvinceId($outlet->provinsi);
                $originCityId = $this->rajaOngkir->getCityId($outlet->kota_kab, $originProvinceId);
                $originDistrictId = $this->rajaOngkir->getDistrictId($outlet->kecamatan, $originCityId);

                // DESTINATION
                $destinationProvinceId = $this->rajaOngkir->getProvinceId($request->provinsi);
                $destinationCityId = $this->rajaOngkir->getCityId($request->kota_kab, $destinationProvinceId);
                $destinationDistrictId = $this->rajaOngkir->getDistrictId($request->kecamatan, $destinationCityId);

                if (!$originDistrictId || !$destinationDistrictId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'District ID tidak ditemukan'
                    ], 422);
                }

                $biayaOngkir = $this->rajaOngkir->calculateDistrictCost(
                    $originDistrictId,
                    $destinationDistrictId,
                    $beratTotal,
                    $request->kurir
                );

                if (!$biayaOngkir) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal menghitung ongkir'
                    ], 422);
                }
            }

            /**
             * ==============================
             * METODE DIAMBIL
             * ==============================
             */
            // if ($request->metode_pengiriman === 'diambil') {
            //     $outletId = $outlet->id;
            // }

            $totalBayar = $subtotal + $biayaOngkir;

            $pesanan = Pesanan::create([
                'no_pesanan' => $this->generateNoPesanan(),
                'pembeli_id' => $user->pembeli_id,
                'ikm_id' => $keranjang->ikm_id,
                'outlet_id' => $outletId,
                'metode_pengiriman' => $request->metode_pengiriman,
                'total_bayar' => $totalBayar,
                'status_pesanan' => 'pending'
            ]);

            foreach ($keranjang->detail as $item) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item->produk_id,
                    'qty' => $item->qty,
                    'harga' => $item->harga
                ]);

                // ✅ TAMBAHAN: KURANGI STOK
                $produk = $item->produk;
                if ($produk) {
                    $produk->decrement('stok', $item->qty);
                }
            }

            if ($request->metode_pengiriman === 'dikirim') {

                AlamatPengiriman::create([
                    'pesanan_id' => $pesanan->id,
                    'nama_penerima' => $request->nama_penerima,
                    'no_hp' => $request->no_hp,
                    'provinsi' => $request->provinsi,
                    'kota_kab' => $request->kota_kab,
                    'kecamatan' => $request->kecamatan,
                    'kode_pos' => $request->kode_pos,
                    'alamat_lengkap' => $request->alamat_lengkap,
                ]);

                Pengiriman::create([
                    'pesanan_id' => $pesanan->id,
                    'kurir' => $request->kurir,
                    'ongkir' => $biayaOngkir,
                    'no_resi' => null
                ]);
            }

            $keranjang->update(['status' => 'checkout']);

            return response()->json([
                'success' => true,
                'message' => 'Checkout berhasil',
                'data' => [
                    'pesanan_id' => $pesanan->id,
                    'subtotal' => $subtotal,
                    'ongkir' => $biayaOngkir,
                    'total_bayar' => $totalBayar,
                    'ikm_rekening' => [
                        'no_rekening' => $keranjang->ikm->no_rekening,
                        'jenis_rekening' => $keranjang->ikm->jenis_rekening,
                        'nama_rekening' => $keranjang->ikm->nama_rekening,
                    ],
                ]
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Checkout gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * LIST PESANAN PEMBELI
     */
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();

            $pesanan = Pesanan::with([
                'detail.produk',
                'pengiriman',
                'outlet',
                'pembayaran',
                'alamat'
            ])
                ->where('pembeli_id', $user->pembeli_id)
                ->latest()
                ->get()
                ->map(function ($item) {

                    // 🔥 FIX LOGIC
                    if ($item->pembayaran) {
                        $item->status_pembayaran = $item->pembayaran->status_pembayaran;
                    } else {
                        $item->status_pembayaran = null;
                    }

                    return $item;
                });

            return response()->json([
                'success' => true,
                'data' => $pesanan
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * SELESAIKAN PESANAN
     */
    public function selesai($id): JsonResponse
    {
        try {
            $user = Auth::user();

            $pesanan = Pesanan::where('pembeli_id', $user->pembeli_id)
                ->findOrFail($id);

            if ($pesanan->status_pesanan !== 'dikirim') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan belum dalam status dikirim'
                ], 400);
            }

            $pesanan->update([
                'status_pesanan' => 'selesai'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan diselesaikan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
