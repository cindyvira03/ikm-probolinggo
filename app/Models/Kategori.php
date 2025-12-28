<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'nama_kategori',
        'slug',
    ];

    public function setNamaKategoriAttribute($value)
    {
        $this->attributes['nama_kategori'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function ikm()
    {
        return $this->hasMany(ProfilIkm::class, 'kategori_id');
    }
}
