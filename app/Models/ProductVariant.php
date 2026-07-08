<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = "product_variants";

    protected $fillable = [
        "product_id",
        "color_id",
        "size_id",
        "sku",
        "stock",
        "price",
        "status",
        "created_by",
        "updated_by",
    ];

    public function product(){
        return $this->belongsTo(Product::class, "product_id");
    }

    public function color()
    {
        return $this->belongsTo(LookupValue::class, 'color_id');
    }

    public function size()
    {
        return $this->belongsTo(LookupValue::class, 'size_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
