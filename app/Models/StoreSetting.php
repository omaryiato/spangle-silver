<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreSetting extends Model
{
    protected $table = 'store_settings';

    protected $fillable = [
        'name_ar', 'name_en', 'description_ar', 'description_en',
        'logo_path', 'favicon_path', 'email_address', 'phone_number',
        'whatsapp_number', 'currency_code', 'social_links', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return ['social_links' => 'object', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
