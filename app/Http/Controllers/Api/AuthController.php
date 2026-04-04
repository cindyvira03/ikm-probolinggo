<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProfilIkm;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REGISTER IKM
    |--------------------------------------------------------------------------
    */
    public function registerIkm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_usaha' => 'required|string|max:255',
            'no_telp' => ['required', 'regex:/^62\d+$/'],
            'merek' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',

            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'no_telp.regex' => 'No. telepon harus diawali 62',
            'password.confirmed' => 'Password tidak sama',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $profilIkm = ProfilIkm::create([
            'nama_usaha' => $request->nama_usaha,
            'slug' => Str::slug($request->nama_usaha),
            'merek' => $request->merek,
            'kategori_id' => $request->kategori_id,
            'status' => 'pending',
            'no_telp' => $request->no_telp,
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // HASH DI SINI
            'role' => 'ikm',
            'ikm_id' => $profilIkm->id,
        ]);

        return response()->json([
            'message' => 'Registrasi IKM berhasil',
            'user' => $user
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER PEMBELI
    |--------------------------------------------------------------------------
    */
    public function registerPembeli(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|string|max:20',

            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            'password.confirmed' => 'Password tidak sama',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $pembeli = Pembeli::create([
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp,
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // HASH
            'role' => 'pembeli',
            'pembeli_id' => $pembeli->id,
        ]);

        return response()->json([
            'message' => 'Registrasi pembeli berhasil',
            'user' => $user
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email / Password salah'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | USER (Session check)
    |--------------------------------------------------------------------------
    */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout berhasil',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat logout',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CONFIRM PASSWORD
    |--------------------------------------------------------------------------
    */
    public function confirmPassword(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        if (!Hash::check($request->password, $request->user()->password)) {
            return response()->json([
                'message' => 'Password salah'
            ], 422);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return response()->json([
            'message' => 'Password terkonfirmasi'
        ]);
    }
}
