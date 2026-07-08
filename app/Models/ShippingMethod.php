<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $table = "shipping_methods";

    protected $fillable = [
        "method_en_name",
        "method_ar_name",
        "price",
        "estimated_days",
        "status"
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'shipping_id');
    }

}
