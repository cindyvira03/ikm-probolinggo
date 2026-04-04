<?php

namespace App\Http\Controllers\Api\Ikm;

use App\Http\Controllers\Controller;
use App\Models\OutletIkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use App\Services\RajaOngkirService;

class OutletController extends Controller
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

    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();

            $outlets = OutletIkm::where('ikm_id', $user->ikm_id)
                ->with('profilIkm')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $outlets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data outlet',
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
            'alamat' => 'required|string|max:255',
            'lokasi_googlemap' => 'required|string|max:255',
            'foto_lokasi_tampak_depan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            // 'cara_order' => 'required|string',
            'provinsi' => 'required|string',
            'kota_kab' => 'required|string',
            'kecamatan' => 'required|string',
        ]);

        try {
            $data = $request->only([
                'alamat',
                'lokasi_googlemap',
                'cara_order',
                'provinsi',
                'kota_kab',
                'kecamatan'
            ]);

            $data['ikm_id'] = $user->ikm_id;

            if ($request->hasFile('foto_lokasi_tampak_depan')) {
                $data['foto_lokasi_tampak_depan'] =
                    $request->file('foto_lokasi_tampak_depan')
                    ->store('outlet-photos', 'public');
            }

            $outlet = OutletIkm::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Outlet berhasil ditambahkan',
                'data' => $outlet
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan outlet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $outlet = OutletIkm::with('profilIkm')->findOrFail($id);

            if ($outlet->ikm_id !== $user->ikm_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $outlet
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Outlet tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $outlet = OutletIkm::findOrFail($id);

            if ($outlet->ikm_id !== $user->ikm_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $request->validate([
                'alamat' => 'required|string|max:255',
                'lokasi_googlemap' => 'required|string|max:255',
                'foto_lokasi_tampak_depan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                // 'cara_order' => 'required|string',
                'remove_foto' => 'nullable|boolean',
                'provinsi' => 'required|string',
                'kota_kab' => 'required|string',
                'kecamatan' => 'required|string',
            ]);

            $data = $request->only([
                'alamat',
                'lokasi_googlemap',
                // 'cara_order',
                'provinsi',
                'kota_kab',
                'kecamatan'
            ]);

            if ($request->remove_foto) {
                if ($outlet->foto_lokasi_tampak_depan) {
                    Storage::disk('public')->delete($outlet->foto_lokasi_tampak_depan);
                }
                $data['foto_lokasi_tampak_depan'] = null;
            }

            if ($request->hasFile('foto_lokasi_tampak_depan')) {
                if ($outlet->foto_lokasi_tampak_depan) {
                    Storage::disk('public')->delete($outlet->foto_lokasi_tampak_depan);
                }

                $data['foto_lokasi_tampak_depan'] =
                    $request->file('foto_lokasi_tampak_depan')
                    ->store('outlet-photos', 'public');
            }

            $outlet->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Outlet berhasil diperbarui',
                'data' => $outlet
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui outlet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $user = Auth::user();
            $outlet = OutletIkm::findOrFail($id);

            if ($outlet->ikm_id !== $user->ikm_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            if ($outlet->foto_lokasi_tampak_depan) {
                Storage::disk('public')->delete($outlet->foto_lokasi_tampak_depan);
            }

            $outlet->delete();

            return response()->json([
                'success' => true,
                'message' => 'Outlet berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus outlet',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
