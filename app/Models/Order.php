<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    // use SoftDeletes;

    protected $table = 'orders';

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function shipping()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_id');
    }

    public function items()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(PaymentTransaction::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
