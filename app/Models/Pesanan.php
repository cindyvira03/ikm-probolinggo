<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $fillable = [
        'no_pesanan',
        'pembeli_id',
        'ikm_id',
        'outlet_id',
        'metode_pengiriman',
        'total_bayar',
        'status_pesanan'
    ];

    public function detail()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class);
    }

    public function alamat()
    {
        return $this->hasOne(AlamatPengiriman::class);
    }

    public function outlet()
    {
        return $this->belongsTo(OutletIkm::class);
    }

    public function ikm()
    {
        return $this->belongsTo(ProfilIkm::class);
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id');
    }
}
