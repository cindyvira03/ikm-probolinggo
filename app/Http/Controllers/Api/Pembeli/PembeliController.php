<?php

namespace App\Http\Controllers\Api\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembeli;

class PembeliController extends Controller
{
    public function profile(): JsonResponse
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user->pembeli) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pembeli tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'pembeli' => $user->pembeli
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil profil pembeli',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $pembeli = $user->pembeli;

            if (!$pembeli) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pembeli tidak ditemukan'
                ], 404);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,

                'nama_lengkap' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:L,P',
                'no_hp' => 'required|string|max:20',
            ], [
                'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            ]);

            /** Update tabel users */
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            /** Update tabel pembeli */
            $pembeli->nama_lengkap = $request->nama_lengkap;
            $pembeli->jenis_kelamin = $request->jenis_kelamin;
            $pembeli->no_hp = $request->no_hp;
            $pembeli->save();

            return response()->json([
                'success' => true,
                'message' => 'Profil pembeli berhasil diperbarui',
                'data' => [
                    'user' => $user,
                    'pembeli' => $pembeli
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profil pembeli',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
