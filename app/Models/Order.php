<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    // use SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'address_id',
        'shipping_id',
        'subtotal',
        'shipping_cost',
        'discount',
        'total_price',
        'status',
        'notes',
        'snap_user_name',
        'snap_address',
        'snap_city',
        'snap_country',
        'snap_phone',
        'snap_email',
        'snap_postal_code',
        'created_by',
        'updated_by',
    ];

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

    public function payment()
    {
        return $this->hasOne(PaymentTransaction::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
