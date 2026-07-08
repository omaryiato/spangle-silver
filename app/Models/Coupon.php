<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = "coupons";
    protected $fillable = [
        "code",
        "discount_amount",
        "minimum_order_amount",
        "max_usage",
        "used_count",
        "expires_at",
        "status",
        "created_by",
        "updated_by",
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }
}
