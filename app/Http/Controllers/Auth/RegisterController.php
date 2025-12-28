<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProfilIkm;
use App\Models\Kategori;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Buglinjo\LaravelWebp\Facades\Webp;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = 'user-area';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     */
    public function showRegistrationForm()
    {
        $kategoris = Kategori::all();
        return view('auth.register', compact('kategoris'));
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            // Data Profil IKM
            'nama_usaha' => ['required', 'string', 'max:255'],
            'no_telp' => ['required', 'string', 'max:20', 'regex:/^62\d+$/'], // harus diawali 62
            'merek' => ['required', 'string', 'max:255'],
            'kategori_id' => ['required', 'exists:kategori,id'],

            // Data User
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            // tambahkan message indonesia
            'nama_usaha.required' => 'Nama usaha harus diisi',
            'no_telp.required' => 'No. telepon harus diisi',
            'no_telp.regex' => 'No. telepon harus diawali 62',
            'merek.required' => 'Merek harus diisi',
            'kategori_id.required' => 'Kategori harus diisi',
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email harus berformat valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Password dan konfirmasi password tidak sama',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        $profilIkm = ProfilIkm::create([
            'nama_usaha' => $data['nama_usaha'],
            'slug' => Str::slug($data['nama_usaha']),
            'merek' => $data['merek'],
            'kategori_id' => $data['kategori_id'],
            'status' => 'pending',
            'no_telp' => $data['no_telp'],
        ]);

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'Pengguna',
            'ikm_id' => $profilIkm->id,
        ]);
    }
}
