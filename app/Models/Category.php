<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Products;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'category';
    protected $primary = 'id';

    protected $fillable = [
        'category_en_name',
        'category_ar_name',
        'category_description',
        'category_image',
        'status',
        'created_by',
        'updated_by',
    ];

    public function products(){
        return  $this->hasMany(Products::class, 'category_id');
    }


}
