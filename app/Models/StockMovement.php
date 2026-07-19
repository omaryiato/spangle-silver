<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = 'stock_movements';

    protected $fillable = [
        'variant_id',
        'movement_type',
        'quantity',
        'stock_after',
        'reference_type',
        'reference_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'variant_id', 'id');
    }
}
