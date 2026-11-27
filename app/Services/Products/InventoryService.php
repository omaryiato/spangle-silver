<?php

declare(strict_types=1);

namespace App\Services\Products;

use App\Repositories\Products\InventoryRepository;
use App\Services\ActingUserService;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InventoryService
{
    public function __construct(private InventoryRepository $repository, private ActingUserService $actor) {}

    public function lockAvailable(array $quantities): Collection
    {
        $variants = $this->repository->lockVariants(array_keys($quantities));
        foreach ($quantities as $id => $quantity) {
            $variant = $variants->get($id);
            if (! $variant || (int) $variant->status !== 1 || ! $variant->product || (int) $variant->product->product_status !== 1 || (int) $variant->stock < $quantity) {
                throw new HttpException(409, 'Product unavailable or insufficient stock.');
            }
        }

        return $variants;
    }

    /** Caller owns the transaction. Locks are acquired in ID order to reduce deadlocks. */
    public function deduct(array $quantities): void
    {
        $variants = $this->lockAvailable($quantities);
        foreach ($quantities as $id => $quantity) {
            $this->repository->decrement($variants->get($id), $quantity, $this->actor->id());
        }
    }
}
