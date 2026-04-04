<?php

namespace App\Http\Controllers\Api\Ikm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProfilIkm;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;


class IkmController extends Controller
{
    public function profile(): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user->profilIkm) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil IKM belum tersedia'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'profil_ikm' => $user->profilIkm,
                    'kategoris' => Kategori::all()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil profil IKM',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {

            /** ambil user login */
            /** @var \App\Models\User $user */
            $user = Auth::user();

            /** ambil profil ikm */
            $profilIkm = $user->profilIkm;

            if (!$profilIkm) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil IKM tidak ditemukan'
                ], 404);
            }

            /** VALIDASI */
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'nama_usaha' => 'required|string|max:255',
                'no_telp' => 'required|string|max:20',
                'merek' => 'nullable|string|max:255',
                'deskripsi_singkat' => 'nullable|string',
                'kategori_id' => 'required|exists:kategori,id',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
                'delete_gambar' => 'nullable|boolean',
                'no_rekening' => 'nullable|string',
                'jenis_rekening' => 'nullable|string',
                'nama_rekening' => 'nullable|string',
            ]);

            /** update user */
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->save();

            /** update profil ikm */
            $profilIkm->nama_usaha = $validated['nama_usaha'];
            $profilIkm->no_telp = $validated['no_telp'];
            $profilIkm->merek = $validated['merek'] ?? null;
            $profilIkm->deskripsi_singkat = $validated['deskripsi_singkat'] ?? null;
            $profilIkm->kategori_id = $validated['kategori_id'];
            $profilIkm->slug = Str::slug($validated['nama_usaha']);
            $profilIkm->no_rekening = $validated['no_rekening'] ?? null;
            $profilIkm->jenis_rekening = $validated['jenis_rekening'] ?? null;
            $profilIkm->nama_rekening = $validated['nama_rekening'] ?? null;

            /** hapus gambar */
            if ($request->delete_gambar && $profilIkm->gambar) {
                Storage::disk('public')->delete('ikm/' . $profilIkm->gambar);
                $profilIkm->gambar = null;
            }

            /** upload gambar */
            if ($request->hasFile('gambar')) {

                if ($profilIkm->gambar) {
                    Storage::disk('public')->delete('ikm/' . $profilIkm->gambar);
                }

                $fileName = time() . '_' . Str::slug($validated['nama_usaha']) . '.' .
                    $request->file('gambar')->getClientOriginalExtension();

                $request->file('gambar')->storeAs('ikm', $fileName, 'public');

                $profilIkm->gambar = $fileName;
            }

            $profilIkm->save();

            return response()->json([
                'success' => true,
                'message' => 'Profil IKM berhasil diperbarui',
                'data' => $profilIkm
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
