<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $fillable = ['pesanan_id', 'bukti_transfer', 'status_pembayaran', 'keterangan'];
    protected $appends = ['bukti_transfer_url'];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function getBuktiTransferUrlAttribute()
    {
        return asset('storage/' . $this->bukti_transfer);
    }
}
