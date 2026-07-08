<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'payment_transactions';

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
