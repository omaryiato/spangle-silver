<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteTheme extends Model
{
    //

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
