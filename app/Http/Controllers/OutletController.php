<?php

namespace App\Http\Controllers;

use App\Models\OutletIkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Buglinjo\LaravelWebp\Facades\Webp;

class OutletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $outlets = OutletIkm::where('ikm_id', $user->ikm_id)
            ->with('profilIkm')
            ->paginate(10);

        return view('pages.user.outlets.index', compact('outlets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user->ikm_id) {
            return redirect()->route('user.outlet.index')
                ->with('error', 'Anda harus memiliki profil IKM terlebih dahulu.');
        }

        return view('pages.user.outlets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'alamat' => 'required|string|max:255',
            'lokasi_googlemap' => 'required|string|max:255',
            'foto_lokasi_tampak_depan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5242880',
            'cara_order' => 'required|string',
        ], [
            'alamat.required' => 'Alamat outlet wajib diisi',
            'alamat.string' => 'Alamat harus berupa teks',
            'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter',
            'lokasi_googlemap.required' => 'Link lokasi Google Maps wajib diisi',
            'lokasi_googlemap.string' => 'Link lokasi harus berupa teks',
            'lokasi_googlemap.max' => 'Link lokasi tidak boleh lebih dari 255 karakter',
            'foto_lokasi_tampak_depan.image' => 'File harus berupa gambar',
            'foto_lokasi_tampak_depan.mimes' => 'Format gambar yang diperbolehkan: jpeg, png, jpg, gif',
            'foto_lokasi_tampak_depan.max' => 'Ukuran gambar tidak boleh lebih dari 5MB',
            'cara_order.required' => 'Cara pemesanan wajib diisi',
            'cara_order.string' => 'Cara pemesanan harus berupa teks'
        ]);

        $data = $request->all();
        $data['ikm_id'] = $user->ikm_id;

        // Handle file upload and convert to WebP
        if ($request->hasFile('foto_lokasi_tampak_depan')) {
            $file = $request->file('foto_lokasi_tampak_depan');
            $filename = time() . '_' . Str::random(10) . '.webp';
            $webpPath = 'outlet-photos/' . $filename;

            // Convert to WebP and save
            $webp = Webp::make($file);
            if ($webp->save(storage_path('app/public/' . $webpPath), 80)) {
                $data['foto_lokasi_tampak_depan'] = $webpPath;
            } else {
                // Fallback to original upload if WebP conversion fails
                $data['foto_lokasi_tampak_depan'] = $file->store('outlet-photos', 'public');
            }
        }

        OutletIkm::create($data);

        return redirect()->route('user.home')
            ->with('success', 'Outlet berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OutletIkm $outlet)
    {
        $user = Auth::user();

        // Check if outlet belongs to current user's IKM
        if ($outlet->ikm_id !== $user->ikm_id) {
            abort(403, 'Unauthorized access.');
        }

        return view('outlets.show', compact('outlet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OutletIkm $outlet)
    {
        $user = Auth::user();

        // Check if outlet belongs to current user's IKM
        if ($outlet->ikm_id !== $user->ikm_id) {
            abort(403, 'Unauthorized access.');
        }

        return view('pages.user.outlets.edit', compact('outlet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OutletIkm $outlet)
    {
        $user = Auth::user();

        // Check if outlet belongs to current user's IKM
        if ($outlet->ikm_id !== $user->ikm_id) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'alamat' => 'required|string|max:255',
            'lokasi_googlemap' => 'required|string|max:255',
            'foto_lokasi_tampak_depan' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5242880',
            'cara_order' => 'required|string',
        ], [
            'alamat.required' => 'Alamat outlet wajib diisi',
            'alamat.string' => 'Alamat harus berupa teks',
            'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter',
            'lokasi_googlemap.required' => 'Link lokasi Google Maps wajib diisi',
            'lokasi_googlemap.string' => 'Link lokasi harus berupa teks',
            'lokasi_googlemap.max' => 'Link lokasi tidak boleh lebih dari 255 karakter',
            'foto_lokasi_tampak_depan.image' => 'File harus berupa gambar',
            'foto_lokasi_tampak_depan.mimes' => 'Format gambar yang diperbolehkan: jpeg, png, jpg, gif, webp',
            'foto_lokasi_tampak_depan.max' => 'Ukuran gambar tidak boleh lebih dari 5MB',
            'cara_order.required' => 'Cara pemesanan wajib diisi',
            'cara_order.string' => 'Cara pemesanan harus berupa teks'
        ]);

        $data = $request->all();

        // Handle remove foto
        if ($request->has('remove_foto') && $request->remove_foto == '1') {
            if ($outlet->foto_lokasi_tampak_depan) {
                Storage::disk('public')->delete($outlet->foto_lokasi_tampak_depan);
            }
            $data['foto_lokasi_tampak_depan'] = null;
        }

        // Handle file upload and convert to WebP
        if ($request->hasFile('foto_lokasi_tampak_depan')) {
            // Delete old photo if exists
            if ($outlet->foto_lokasi_tampak_depan) {
                Storage::disk('public')->delete($outlet->foto_lokasi_tampak_depan);
            }

            $file = $request->file('foto_lokasi_tampak_depan');
            $filename = time() . '_' . Str::random(10) . '.webp';
            $webpPath = 'outlet-photos/' . $filename;

            // Convert to WebP and save
            $webp = Webp::make($file);
            if ($webp->save(storage_path('app/public/' . $webpPath), 80)) {
                $data['foto_lokasi_tampak_depan'] = $webpPath;
            } else {
                // Fallback to original upload if WebP conversion fails
                $data['foto_lokasi_tampak_depan'] = $file->store('outlet-photos', 'public');
            }
        }

        $outlet->update($data);

        return redirect()->route('user.outlet.index')
            ->with('success', 'Outlet berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OutletIkm $outlet)
    {
        $user = Auth::user();

        // Check if outlet belongs to current user's IKM
        if ($outlet->ikm_id !== $user->ikm_id) {
            abort(403, 'Unauthorized access.');
        }

        // Delete photo if exists
        if ($outlet->foto_lokasi_tampak_depan) {
            Storage::disk('public')->delete($outlet->foto_lokasi_tampak_depan);
        }

        $outlet->delete();

        return redirect()->route('user.outlet.index')
            ->with('success', 'Outlet berhasil dihapus.');
    }
}
