<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteTheme extends Model
{
    protected $table = "site_theme";

    protected $fillable = [
        'theme_name',
        'color_scheme',
        'font_style',
        'background_image',
        'borders',
        'status',
        'alt_text',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
