<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $table = 'seo_settings';

    protected $fillable = [
        'page',
        'keywords',
        'page_title',
        'meta_description',
        'meta_author',
        'meta_robots',
        'heading_h1',
        'canonical_url',
        'hero_image',
        'image_alt',
        'enable_sitemap',
        'enable_robots',
    ];
}
