<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'ikm_id',
        'nama_produk',
        'jenis_produk',
        'harga',
        'stok',
        'berat',
        'deskripsi',
        'varian',
        'ukuran',
        'foto',
    ];

    /**
     * Relasi ke ProfilIkm
     */
    public function ikm()
    {
        return $this->belongsTo(ProfilIkm::class, 'ikm_id');
    }
}
