<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';
    protected $fillable = ['pesanan_id', 'kurir', 'layanan', 'ongkir', 'no_resi'];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
