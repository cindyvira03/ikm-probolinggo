<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriBerita extends Model
{
    protected $table = 'kategori_berita';

    protected $fillable = [
        'nama_kategori',
        'slug',
    ];

    public function setNamaKategoriAttribute($value)
    {
        $this->attributes['nama_kategori'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
