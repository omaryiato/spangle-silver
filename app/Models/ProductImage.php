<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = "product_images";
    
    protected $fillable = [
        "product_id",
        "image",
        "is_primary",
        "sort_order",
        "created_by",
        "updated_by",
    ];


    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function product(){
        return $this->belongsTo(Product::class, "product_id", 'id');
    }

}
