<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    //

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
