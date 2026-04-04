<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjang';
    protected $fillable = ['pembeli_id', 'ikm_id', 'status'];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class);
    }

    public function detail()
    {
        return $this->hasMany(DetailKeranjang::class);
    }

    public function ikm()
    {
        return $this->belongsTo(ProfilIkm::class, 'ikm_id');
    }
}
