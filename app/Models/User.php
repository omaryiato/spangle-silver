<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // use SoftDeletes;
    use HasApiTokens;

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
        'updated_by',
    ];

    protected $casts = [
        'password' => 'hashed',
        'status' => 'integer',
        'user_type' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function cart()
    {
        return $this->hasMany(CartProduct::class, 'user_id', 'id');
    }

    public function wishlist()
    {
        return $this->hasMany(UserWishlist::class, 'user_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'user_id', 'id');
    }
}
