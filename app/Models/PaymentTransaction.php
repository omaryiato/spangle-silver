<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransaction extends Model
{
    // use SoftDeletes;

    protected $table = 'payment_transactions';

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
