<?php

namespace App\Http\Controllers\Api\Ikm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\OutletIkm;
use App\Models\Ikm;
use App\Models\ProfilIkm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $ikmId = Auth::user()->ikm_id;

        // ========================
        // FILTER BULAN
        // ========================
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;

        // ========================
        // TOTAL PRODUK
        // ========================
        $totalProduk = Produk::where('ikm_id', $ikmId)->count();

        // ========================
        // TOTAL OUTLET
        // ========================
        $totalOutlet = OutletIkm::where('ikm_id', $ikmId)->count();

        // ========================
        // PESANAN HARI INI
        // ========================
        $pesananHariIni = Pesanan::where('ikm_id', $ikmId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // ========================
        // PESANAN PENDING
        // ========================
        $pesananPending = Pesanan::where('ikm_id', $ikmId)
            ->where('status_pesanan', 'pending')
            ->count();

        // ========================
        // PRODUK TERLARIS
        // ========================
        $produkTerlaris = DetailPesanan::select(
            'produk_id',
            DB::raw('SUM(qty) as total_terjual')
        )
            ->join('pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
            ->where('pesanan.ikm_id', $ikmId)
            ->groupBy('produk_id')
            ->orderByDesc('total_terjual')
            ->with('produk')
            ->first();

        // ========================
        // OMSET BULAN DIPILIH
        // ========================
        $omsetBulan = Pesanan::join('pembayaran', 'pesanan.id', '=', 'pembayaran.pesanan_id')
            ->where('pesanan.ikm_id', $ikmId)
            ->where('pembayaran.status_pembayaran', 'valid') // ✅ FILTER PENTING
            ->whereMonth('pesanan.created_at', $bulan)
            ->whereYear('pesanan.created_at', $tahun)
            ->sum('pesanan.total_bayar');

        // ========================
        // GRAFIK OMSET HARIAN
        // ========================
        $grafikOmset = Pesanan::select(
            DB::raw('DATE(pesanan.created_at) as tanggal'),
            DB::raw('SUM(pesanan.total_bayar) as omset')
        )
            ->join('pembayaran', 'pesanan.id', '=', 'pembayaran.pesanan_id')
            ->where('pesanan.ikm_id', $ikmId)
            ->where('pembayaran.status_pembayaran', 'valid') // ✅ FILTER
            ->whereMonth('pesanan.created_at', $bulan)
            ->whereYear('pesanan.created_at', $tahun)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // ========================
        // CEK PROFIL
        // ========================
        $ikm = ProfilIkm::find($ikmId);

        $fields = [
            $ikm->nama_usaha,
            $ikm->no_telp,
            $ikm->slug,
            $ikm->merek,
            $ikm->deskripsi_singkat,
            $ikm->kategori_id,
            $ikm->gambar,
            $ikm->no_rekening,
            $ikm->jenis_rekening,
            $ikm->nama_rekening
        ];

        $totalField = count($fields);
        $filledField = 0;

        foreach ($fields as $field) {
            if (!empty($field)) {
                $filledField++;
            }
        }

        $profilProgress = round(($filledField / $totalField) * 100);
        $profilLengkap = ((int)$profilProgress === 100); // cast ke int

        // ========================
        // RESPONSE
        // ========================
        return response()->json([
            'success' => true,
            'data' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'total_produk' => $totalProduk,
                'total_outlet' => $totalOutlet,
                'pesanan_hari_ini' => $pesananHariIni,
                'pesanan_pending' => $pesananPending,
                'produk_terlaris' => $produkTerlaris,
                'omset_bulan' => $omsetBulan,
                'grafik_omset' => $grafikOmset,
                'profil_lengkap' => $profilLengkap,
                'profil_progress' => $profilProgress
            ]
        ]);
    }
}
