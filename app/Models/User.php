<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Model
{
    use SoftDeletes;

    protected $table = 'users';
    protected $fillable = [
        'full_name',
        'user_name',
        'phone_number',
        'email_address',
        'password',
        'status',
        'user_type',
        'created_by',
        'updated_by'
    ];

    protected $guarded = [];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cart()
    {
        return $this->hasMany(CartProduct::class);
    }

    public function wishlist()
    {
        return $this->hasMany(UserWishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}

