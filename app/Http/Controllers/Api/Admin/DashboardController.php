<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutletIkm;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'jumlah_ikm' => User::where('role', 'ikm')->count(),
                    'jumlah_produk' => Produk::count(),
                    'jumlah_outlet' => OutletIkm::count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
