<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\LookupValue;



class LookupType extends Model
{
    use SoftDeletes;
    protected $table = 'lookup_types';

    protected $fillable = [
        'type_en_name',
        'type_ar_name',
        'type_desc',
        'status',
        'created_by',
        'updated_by',
    ];

    public $timestamps = false; // cus we already do it postgre

    public function values()
    {
        return $this->hasMany(LookupValue::class, 'type_id');
    }
}
