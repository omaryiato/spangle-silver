<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    // use SoftDeletes;

    protected $table = 'orders_details';

    protected $fillable = [
        'order_id',
        'variant_id',
        'quantity',
        'unit_price',
        'total_price',
        'created_by',
        'updated_by'
    ];

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

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
