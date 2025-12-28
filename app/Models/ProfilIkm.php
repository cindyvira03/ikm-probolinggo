<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilIkm extends Model
{
    protected $table = 'profil_ikm';

    protected $fillable = [
        'nama_usaha',
        'no_telp',
        'slug',
        'merek',
        'deskripsi_singkat',
        'kategori_id',
        'gambar',
        'status',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function outlets()
    {
        return $this->hasMany(OutletIkm::class, 'ikm_id');
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, 'ikm_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'ikm_id', 'id');
    }
}
