<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    protected $table = 'pembeli';
    protected $fillable = ['nama_lengkap', 'jenis_kelamin', 'no_hp'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class);
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }
}
