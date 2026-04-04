<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlamatPengiriman extends Model
{
    protected $table = 'alamat_pengiriman';
    protected $fillable = [
        'pesanan_id',
        'nama_penerima',
        'no_hp',
        'provinsi',
        'kota_kab',
        'kecamatan',
        'kode_pos',
        'alamat_lengkap'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
