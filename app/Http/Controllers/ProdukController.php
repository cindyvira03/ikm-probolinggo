<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Buglinjo\LaravelWebp\Facades\Webp;

class ProdukController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $produk = Produk::where('ikm_id', $user->ikm_id)
            ->with('ikm')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.user.produk.index', compact('produk'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->ikm_id) {
            return redirect()->route('user.produk.index')
                ->with('error', 'Anda harus memiliki profil IKM terlebih dahulu.');
        }

        return view('pages.user.produk.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'jenis_produk' => 'required|string|max:255',
            'harga' => 'required|integer',
            'deskripsi' => 'required|string',
            'varian' => 'nullable|string|max:255',
            'ukuran' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi',
            'nama_produk.string' => 'Nama produk harus berupa teks',
            'nama_produk.max' => 'Nama produk maksimal 255 karakter',
            'jenis_produk.required' => 'Jenis produk wajib diisi',
            'jenis_produk.string' => 'Jenis produk harus berupa teks',
            'jenis_produk.max' => 'Jenis produk maksimal 255 karakter',
            'harga.required' => 'Harga wajib diisi',
            'harga.integer' => 'Harga harus berupa angka',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'deskripsi.string' => 'Deskripsi harus berupa teks',
            'varian.string' => 'Varian harus berupa teks',
            'varian.max' => 'Varian maksimal 255 karakter',
            'ukuran.string' => 'Ukuran harus berupa teks',
            'ukuran.max' => 'Ukuran maksimal 255 karakter',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar yang diperbolehkan: jpg, jpeg, png, gif, webp',
            'foto.max' => 'Ukuran gambar maksimal 5MB'
        ]);

        try {
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . Str::random(10) . '.webp';
                $webpPath = 'produk/' . $filename;

                // Ensure directory exists
                $directory = storage_path('app/public/produk');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Convert to WebP and save
                $webp = Webp::make($file);
                if ($webp->save(storage_path('app/public/' . $webpPath), 80)) {
                    $fotoPath = $webpPath;
                } else {
                    // Fallback to original upload if WebP conversion fails
                    $fotoPath = $file->store('produk', 'public');
                }
            }

            // ikm_id otomatis ikut user login
            Produk::create([
                'ikm_id' => $user->ikm_id,
                'nama_produk' => $request->nama_produk,
                'jenis_produk' => $request->jenis_produk,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
                'varian' => $request->varian,
                'ukuran' => $request->ukuran,
                'foto' => $fotoPath,
            ]);

            return redirect()->route('user.produk.index')
                ->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Produk $produk)
    {
        $user = Auth::user();

        // Check if product belongs to current user's IKM
        if ($produk->ikm_id !== $user->ikm_id) {
            abort(403, 'Unauthorized access.');
        }

        $produk->load('ikm');
        return view('pages.user.produk.show', compact('produk'));
    }

    public function edit(Produk $produk)
    {
        $user = Auth::user();

        // Check if produk belongs to current user's IKM
        if ($produk->ikm_id !== $user->ikm_id) {
            abort(403, 'Unauthorized access.');
        }

        return view('pages.user.produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $user = Auth::user();

        // Check if product belongs to current user's IKM
        if ($produk->ikm_id !== $user->ikm_id) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'jenis_produk' => 'required|string|max:255',
            'harga' => 'required|integer',
            'deskripsi' => 'required|string',
            'varian' => 'nullable|string|max:255',
            'ukuran' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'remove_image' => 'nullable|string',
        ]);

        try {
            $data = $request->only([
                'nama_produk',
                'jenis_produk',
                'harga',
                'deskripsi',
                'varian',
                'ukuran'
            ]);

            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image == '1') {
                if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                    Storage::disk('public')->delete($produk->foto);
                }
                $data['foto'] = null;
            }
            // Handle new image upload
            elseif ($request->hasFile('foto')) {
                // Delete old photo if exists
                if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                    Storage::disk('public')->delete($produk->foto);
                }

                $file = $request->file('foto');
                $filename = time() . '_' . Str::random(10) . '.webp';
                $webpPath = 'produk/' . $filename;

                // Ensure directory exists
                $directory = storage_path('app/public/produk');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Convert to WebP and save
                $webp = Webp::make($file);
                if ($webp->save(storage_path('app/public/' . $webpPath), 80)) {
                    $data['foto'] = $webpPath;
                } else {
                    // Fallback to original upload if WebP conversion fails
                    $data['foto'] = $file->store('produk', 'public');
                }
            }

            $produk->update($data);

            return redirect()->route('user.produk.index')
                ->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Produk $produk)
    {
        $user = Auth::user();

        // Check if product belongs to current user's IKM
        if ($produk->ikm_id !== $user->ikm_id) {
            abort(403, 'Unauthorized access.');
        }

        try {
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }

            $produk->delete();

            return redirect()->route('user.produk.index')
                ->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
