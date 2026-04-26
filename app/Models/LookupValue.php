<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LookupValue extends Model
{
    use SoftDeletes;

    protected $table = 'lookup_values';

    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(LookupType::class, 'type_id');
    }
}
