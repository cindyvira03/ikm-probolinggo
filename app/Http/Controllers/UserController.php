<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProfilIkm;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Buglinjo\LaravelWebp\Facades\Webp;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for editing the user profile.
     */
    public function editProfile()
    {
        $user = Auth::user();
        $profilIkm = $user->profilIkm;
        $kategoris = Kategori::all();

        return view('pages.user.profile.edit', compact('user', 'profilIkm', 'kategoris'));
    }

    /**
     * Update the user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $profilIkm = $user->profilIkm;

        // Validasi request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nama_usaha' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'merek' => 'nullable|string|max:255',
            'deskripsi_singkat' => 'nullable|string',
            'kategori_id' => 'required|exists:kategori,id',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'delete_gambar' => 'nullable|boolean',
        ]);

        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Update profil IKM data
        $profilIkm->nama_usaha = $request->nama_usaha;
        $profilIkm->no_telp = $request->no_telp;
        $profilIkm->merek = $request->merek;
        $profilIkm->deskripsi_singkat = $request->deskripsi_singkat;
        $profilIkm->kategori_id = $request->kategori_id;
        $profilIkm->slug = Str::slug($request->nama_usaha);

        // Handle gambar
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($profilIkm->gambar && Storage::exists('public/ikm/' . $profilIkm->gambar)) {
                Storage::delete('public/ikm/' . $profilIkm->gambar);
            }

            // Upload gambar baru
            $file = $request->file('gambar');
            $fileName = time() . '_' . Str::slug($request->nama_usaha) . '.' . $file->getClientOriginalExtension();

            // Convert to WebP if supported
            try {
                $webp = Webp::make($file);
                $webpFileName = time() . '_' . Str::slug($request->nama_usaha) . '.webp';
                $webp->save(storage_path('app/public/ikm/' . $webpFileName));
                $profilIkm->gambar = $webpFileName;
            } catch (\Exception $e) {
                // Fallback to original format if WebP conversion fails
                $file->storeAs('public/ikm', $fileName);
                $profilIkm->gambar = $fileName;
            }
        } elseif ($request->delete_gambar) {
            // Hapus gambar jika checkbox delete dicentang
            if ($profilIkm->gambar && Storage::exists('public/ikm/' . $profilIkm->gambar)) {
                Storage::delete('public/ikm/' . $profilIkm->gambar);
            }
            $profilIkm->gambar = null;
        }

        $profilIkm->save();

        return redirect()->route('user.profile.edit')
            ->with('success', 'Profil berhasil diperbarui');
    }
}
