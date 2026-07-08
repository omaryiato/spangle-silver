<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $table = 'addresses';
    protected $fillable = [
        'user_id',
        'label',
        'full_name',
        'address_line',
        'city',
        'country',
        'postal_code',
        'phone',
        'is_default'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
