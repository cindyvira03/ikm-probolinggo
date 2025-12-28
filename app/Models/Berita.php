<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Berita extends Model
{
    protected $table = 'berita';

    protected $fillable = [
        'judul',
        'slug',
        'kategori_id',
        'gambar',
        'isi',
    ];

    public function setJudulAttribute($value)
    {
        $this->attributes['judul'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Relasi ke kategori berita
    public function kategoriBerita()
    {
        return $this->belongsTo(KategoriBerita::class, 'kategori_id');
    }
}
