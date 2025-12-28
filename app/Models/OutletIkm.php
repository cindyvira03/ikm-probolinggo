<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OutletIkm extends Model
{
    use HasFactory;

    protected $table = 'outlet_ikm';

    protected $fillable = [
        'ikm_id',
        'alamat',
        'lokasi_googlemap',
        'foto_lokasi_tampak_depan',
        'cara_order'
    ];

    // Relasi ke profil IKM
    public function profilIkm()
    {
        return $this->belongsTo(ProfilIkm::class, 'ikm_id');
    }
}
