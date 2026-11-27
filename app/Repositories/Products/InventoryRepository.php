<?php

declare(strict_types=1);

namespace App\Repositories\Products;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Collection;

class InventoryRepository
{
    public function lockVariants(array $ids): Collection
    {
        return ProductVariant::with('product')->whereIn('id', $ids)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
    }

    public function decrement(ProductVariant $variant, int $quantity, int $actorId): void
    {
        $variant->stock = (int) $variant->stock - $quantity;
        $variant->updated_by = $actorId;
        $variant->save();
    }
}
