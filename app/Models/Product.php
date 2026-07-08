<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';
    protected $fillable = [
        'product_en_name',
        'product_ar_name',
        'product_en_description',
        'product_ar_description',
        'product_material',
        'product_stone',
        'product_reels',
        'product_price',
        'product_status',
        'category_id',
        'created_by',
        'updated_by'
    ];

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id', 'id');
    }

    public function material()
    {
        return $this->belongsTo(LookupValue::class, 'product_material');
    }

    public function stone()
    {
        return $this->belongsTo(LookupValue::class, 'product_stone');
    }
}
